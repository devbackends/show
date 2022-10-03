<?php

namespace Webkul\Attribute\Repositories;

use Webkul\Attribute\Models\AttributeOption;
use Webkul\Attribute\Models\AttributeProxy;
use Webkul\Category\Repositories\CategoryRepository;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Webkul\Core\Eloquent\Repository;

class AttributeRepository extends Repository
{
    /**
     * AttributeOptionRepository object
     *
     * @var \Webkul\Attribute\Repositories\AttributeOptionRepository
     */
    protected $attributeOptionRepository;

    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeOptionRepository  $attributeOptionRepository
     * @return void
     */
    public function __construct(
        AttributeOptionRepository $attributeOptionRepository,
        App $app
    )
    {
        $this->attributeOptionRepository = $attributeOptionRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Attribute\Contracts\Attribute';
    }

    /**
     * @param  array  $data
     * @return \Webkul\Attribute\Contracts\Attribute
     */
    public function create(array $data)
    {
        Event::dispatch('catalog.attribute.create.before');

        $data = $this->validateUserInput($data);

        $options = isset($data['options']) ? $data['options'] : [];

        unset($data['options']);

        $attribute = $this->model->create($data);

        if (in_array($attribute->type, ['select', 'multiselect', 'checkbox']) && count($options)) {
            foreach ($options as $optionInputs) {
                $this->attributeOptionRepository->create(array_merge([
                    'attribute_id' => $attribute->id,
                ], $optionInputs));
            }
        }

        Event::dispatch('catalog.attribute.create.after', $attribute);

        return $attribute;
    }

    /**
     * @param  array  $data
     * @param  int $id
     * @param  string  $attribute
     * @return \Webkul\Attribute\Contracts\Attribute
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $data = $this->validateUserInput($data);

        $attribute = $this->find($id);

        Event::dispatch('catalog.attribute.update.before', $id);

        $attribute->update($data);

        $previousOptionIds = $attribute->options()->pluck('id');

        if (in_array($attribute->type, ['select', 'multiselect', 'checkbox'])) {
            if (isset($data['options'])) {
                foreach ($data['options'] as $optionId => $optionInputs) {
                    if (Str::contains($optionId, 'option_')) {
                        $this->attributeOptionRepository->create(array_merge([
                            'attribute_id' => $attribute->id,
                        ], $optionInputs));
                    } else {
                        if (is_numeric($index = $previousOptionIds->search($optionId))) {
                            $previousOptionIds->forget($index);
                        }

                        $this->attributeOptionRepository->update($optionInputs, $optionId);
                    }
                }
            }
        }

/*        foreach ($previousOptionIds as $optionId) {
            $this->attributeOptionRepository->delete($optionId);
        }*/

        Event::dispatch('catalog.attribute.update.after', $attribute);

        return $attribute;
    }

    /**
     * @param  int  $id
     * @return void
     */
    public function delete($id)
    {
        Event::dispatch('catalog.attribute.delete.before', $id);

        parent::delete($id);

        Event::dispatch('catalog.attribute.delete.after', $id);
    }

    /**
     * @param  array  $data
     * @return array
     */
    public function validateUserInput($data)
    {
        if (isset($data['is_configurable'])) {
            if ($data['is_configurable']) {
                $data['value_per_channel'] = $data['value_per_locale'] = 0;
            }
        }


        if (! in_array($data['type'], ['select', 'multiselect', 'price', 'checkbox'])) {
            $data['is_filterable'] = 0;
        }

        if (in_array($data['type'], ['select', 'multiselect', 'boolean'])) {
            unset($data['value_per_locale']);
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getFilterAttributes()
    {
        return $this->model->where('is_filterable', 1)->with('options')->get();
    }

    public function getFilterAttributesWithOptionsAndProductsAmount($sellerId = -1)
    {
        $attrs = $this->model->where('is_filterable', 1)->get();
        $categories =app('Webkul\Category\Repositories\CategoryRepository')->findWhere(['parent_id'=>64])->pluck('id');
        $attributes=[];
        $attributesOptionsProductsAmount=\Webkul\Attribute\Models\AttributesOptionsProductsAmount::query()->selectRaw('attributes_options_products_amount.seller_id,marketplace_sellers.shop_title as shop_title ,attributes_options_products_amount.products_amount,attributes_options_products_amount.option_id,attribute_options.admin_name as option ,attributes.primary_filter,attributes.admin_name as attribute,attributes.code as attribute_code,attributes.id as attribute_id')
            ->join('attribute_options','attribute_options.id','=','attributes_options_products_amount.option_id')
            ->join('attributes','attribute_options.attribute_id','=','attributes.id')
            ->join('marketplace_sellers','marketplace_sellers.id','=','attributes_options_products_amount.seller_id')
            ->where('seller_id','=',$sellerId)
            ->where('products_amount','>',0)
            ->whereIn('attributes.id', $attrs->pluck('id')->toArray())
            ->whereIn('attributes_options_products_amount.category_id',$categories)
            ->orderBy('attributes.primary_filter','desc')
            ->get();

        $attributeCounter=-1;
        $sellerCounter=-1;

        foreach($attributesOptionsProductsAmount as $key => $attributeOptionProductAmount){
            if(!isset($attrsCheck[$attributeOptionProductAmount->attribute_id])){
                $attributeCounter+=1;
                $attrsCheck[$attributeOptionProductAmount->attribute_id]=$attributeCounter;
                $attributes[$attributeCounter]=array('id'=>$attributeOptionProductAmount->attribute_id,'code'=>$attributeOptionProductAmount->attribute_code,'primary_filter'=>$attributeOptionProductAmount->primary_filter,'admin_name'=>$attributeOptionProductAmount->attribute,'options'=>[]);
            }
            $checkFoundBefore=0;
            foreach ($attributes[$attrsCheck[$attributeOptionProductAmount->attribute_id]]['options'] as $key => $option){
                if($option['id']==$attributeOptionProductAmount->option_id){
                    $checkFoundBefore=1;
                    $p_amount=$option['products_amount']+$attributeOptionProductAmount->products_amount;
                    $attributes[$attrsCheck[$attributeOptionProductAmount->attribute_id]]['options'][$key]= array('admin_name'=>$attributeOptionProductAmount->option,'products_amount'=>$p_amount,'id'=>$attributeOptionProductAmount->option_id);
                }
            }
            if(!$checkFoundBefore){
                $attributes[$attrsCheck[$attributeOptionProductAmount->attribute_id]]['options'][count($attributes[$attrsCheck[$attributeOptionProductAmount->attribute_id]]['options'])]= array('admin_name'=>$attributeOptionProductAmount->option,'products_amount'=>$attributeOptionProductAmount->products_amount,'id'=>$attributeOptionProductAmount->option_id);
            }


        }

        foreach ($attributes as $key => $attribute){
            $columns = array_column($attribute['options'], 'admin_name');
            array_multisort($columns, SORT_ASC, $attribute['options']);
            $attributes[$key]['options']=$attribute['options'];
        }
        return array('attributes' => $attributes);
    }

    /**
     *
     * @param  array  $codes
     * @return array
     */
    public function getProductDefaultAttributes($codes = null)
    {
        $attributeColumns  = ['id', 'code', 'value_per_channel', 'value_per_locale', 'type', 'is_filterable'];

        if (! is_array($codes) && ! $codes)
            return $this->findWhereIn('code', [
                'name',
                'description',
                'short_description',
                'url_key',
                'price',
                'special_price',
                'special_price_from',
                'special_price_to',
                'status',
            ], $attributeColumns);

        if (in_array('*', $codes)) {
            return $this->all($attributeColumns);
        }

        return $this->findWhereIn('code', $codes, $attributeColumns);
    }

    /**
     * @param  string  $code
     * @return \Webkul\Attribute\Contracts\Attribute
     */
    public function getAttributeByCode($code)
    {
        static $attributes = [];

        if (array_key_exists($code, $attributes)) {
            return $attributes[$code];
        }

        return $attributes[$code] = $this->findOneByField('code', $code);
    }

    /**
     * @param  \Webkul\Attribute\Contracts\AttributeFamily  $attributeFamily
     * @return \Webkul\Attribute\Contracts\Attribute
     */
    public function getFamilyAttributes($attributeFamily)
    {
        static $attributes = [];

        if (array_key_exists($attributeFamily->id, $attributes)) {
            return $attributes[$attributeFamily->id];
        }

        return $attributes[$attributeFamily->id] = $attributeFamily->custom_attributes;
    }

    /**
     * @return array
     */
    public function getPartial()
    {
        $attributes = $this->model->all();

        $trimmed = [];

        foreach($attributes as $key => $attribute) {
            if ($attribute->code != 'tax_category_id'
                && (
                    $attribute->type == 'select'
                    || $attribute->type == 'multiselect'
                    || $attribute->code == 'sku'
            )) {
                if ($attribute->options()->exists()) {
                    array_push($trimmed, [
                        'id'          => $attribute->id,
                        'name'        => $attribute->admin_name,
                        'type'        => $attribute->type,
                        'code'        => $attribute->code,
                        'has_options' => true,
                        'options'     => $attribute->options,
                    ]);
                } else {
                    array_push($trimmed, [
                        'id'          => $attribute->id,
                        'name'        => $attribute->admin_name,
                        'type'        => $attribute->type,
                        'code'        => $attribute->code,
                        'has_options' => false,
                        'options'     => null,
                    ]);
                }

            }
        }

        return $trimmed;
    }
}