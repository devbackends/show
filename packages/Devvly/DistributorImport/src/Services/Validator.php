<?php

namespace Devvly\DistributorImport\Services;

use DB;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Attribute\Models\AttributeOptionTranslation;

class Validator
{

    /**
     * @var Distributor
     */
    protected $distributor;

    /**
     * @var array
     */
    protected $fileConfig;
    public $attributes;

    /**
     * Validator constructor.
     * @param string $fileConfigType
     */
    public function __construct(string $fileConfigType)
    {
        $this->distributor = app('Devvly\DistributorImport\Services\Distributor');

        $this->fileConfig = config('distimport.files.' . $fileConfigType);
    }

    /**
     * @return array|array[]
     */
    public function execute()
    {
        $keys = $this->fileConfig['keys'];

        $values = $this->getValues($this->fileConfig['content']);

        //filter through our data only
        foreach ($values as $key => $value){
            if (!in_array($value[3],['1','01','2','02','3','03','5','05','7','07','18','23']) ){
                unset($values[$key]);
            }
        }
        // Combine

        $data = array_map(function ($value) use ($keys) {
            if (count($value) !== count($keys)) {
                $diff = count($value) - count($keys);
                $value = array_slice($value, 0, count($value) - $diff);
            }
            return array_combine($keys, $value);
        }, $values);

        if (isset($this->fileConfig['customValidator'])) {
            $data = (new $this->fileConfig['customValidator']($data))->execute();
        }

        return $data;
    }

    /**
     *
     * Parse remote values file
     *
     * @param string $path
     * @return array
     */
    protected function getValues(string $path)
    {
        // Get remote content
        $content = trim($this->distributor->get($path));

        // Parse file by lines
        $content = explode("\n", $content);
        // Parse line by values
        return array_map(function ($value) {
            return explode(';', $value);
        }, $content);
    }

    public function getAttributes()
    {
        $attributes_keys = config('distimport.customValidatorsConfigs.attributes_keys');

        $lines = explode("\n", trim($this->distributor->get($this->fileConfig['attributes'])));
        $i = 0;
        foreach ($lines as $line) {
            $arr[$i] = explode(";", $line);
            if (sizeof($arr[$i]) == sizeof($attributes_keys))
                $result[$i] = array_combine($attributes_keys, $arr[$i]);
            $i++;
        }
        return $result;
    }
    public function getRestrictions()
    {
        // Get remote content
        $content = trim(app('Devvly\DistributorImport\Services\Distributor')->get(config('distimport.files.main.restrictions')));

        // Parse file by lines
        $content = explode("\n", $content);

        return array_map(function ($value) {
            return explode(";", $value);
        }, $content);
    }

    public function getProductWarnings(){
        // Get remote content
        $content = trim(app('Devvly\DistributorImport\Services\Distributor')->get($this->fileConfig['product-warnings']));

        // Parse file by lines
        $content = explode("\n", $content);

        return array_map(function ($value) {
            return explode(";", $value);
        }, $content);
    }

    public function getDescriptions()
    {
        return $this->distributor->get($this->fileConfig['descriptions']);
    }

    function xml2array($xmlObject, $out = array())
    {
        foreach ((array)$xmlObject as $index => $node)
            $out[$index] = (is_object($node)) ? xml2array($node) : $node;
        return $out;
    }

    public function validateProductAttribute($rsr_attribute, $attribute_name)
    {
        if (!empty($rsr_attribute)) {
            $attribute = DB::select("select * from attributes where lower(code)='$attribute_name'");
            if ($attribute) {
                $attribute_id = $attribute[0]->id;

                if ($attribute[0]->type == 'multiselect') {
                    $multiAttributesOptons = explode(',', $rsr_attribute);
                    $arr = array();
                    foreach ($multiAttributesOptons as $attribute_option) {
                        $attribute_option = rtrim(ltrim($attribute_option));
                        $attOption = AttributeOption::where(['admin_name' => $attribute_option])->where(['attribute_id' => $attribute_id])->get();
                        $count=$attOption->count();
                        if ($count == 0) {
                            $attribute_option_id = $this->addProductOption($attribute_option, $attribute_id);
                        } else {
                            $attribute_option_id = AttributeOption::where(['admin_name' => $attribute_option])->where(['attribute_id' => $attribute_id])->get()->first()->id;
                        }
                        array_push($arr, $attribute_option_id);
                    }
                    return  $arr;
                }

                if(strtolower($attribute_name)=='barrel_length'){
                    if(strpos($rsr_attribute, '"') !== false){
                        $rsr_attribute=str_replace('"',' in',$rsr_attribute);
                    }
                }
                $count = AttributeOption::where(['admin_name' => $rsr_attribute])->where(['attribute_id' => $attribute_id])->get()->count();
                if ($count == 0) {
                    $attribute_option_id= $this->addProductOption($rsr_attribute, $attribute_id);
                } else {
                    $attribute_option_id = AttributeOption::where(['admin_name' => $rsr_attribute])->where(['attribute_id' => $attribute_id])->get()->first()->id;
                }
                return $attribute_option_id;
            }

        }

        return '';

    }
    public function addProductOption($rsr_attribute,$attribute_id){
        $attribute_option = new AttributeOption();
        $attribute_option->admin_name = $rsr_attribute;
        $attribute_option->sort_order = 1;
        $attribute_option->attribute_id = $attribute_id;
        $attribute_option->save();
        $attribute_option_translations = new AttributeOptionTranslation();

        $attribute_option_translations->locale = 'en';
        $attribute_option_translations->label = ucfirst($rsr_attribute);
        $attribute_option_translations->attribute_option_id = $attribute_option->id;
        $attribute_option_translations->save();
        return $attribute_option->id;
    }
    public function getRsrDeletedProducts(){
        $values = $this->getValues($this->fileConfig['deleteRsrProducts']);
        return $values;
    }

}