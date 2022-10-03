<?php

namespace Devvly\DistributorImport\CustomValidators;

class InvFileValidator implements CustomValidator
{
    protected $data;

    protected $config;

    /**
     * InvFileValidator constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;

        $this->config = config('distimport.customValidatorsConfigs.'.self::class);
    }

    /**
     * @return array
     */
    public function execute()
    {
        return array_map(function ($item) {

            // State restriction
            $item = $this->setStateRestrictions($item);
            // Family id
            $item = $this->setFamilyId($item);
            // set product type
            $item = $this->setProductType($item);
            // set product category
            $item = $this->setProductCategory($item);

            return $item;

        }, $this->data);
    }

    protected function setStateRestrictions($item)
    {
        $restrictedArray = [];
        $data = array_filter($item, function ($value, $key) use ($restrictedArray) {
            if (isset($this->config['stateRestrictions'][$key])) {
                if ($value == 'Y') {
                    $restrictedArray[] = $key;
                }
                return false;
            } else {
                return true;
            }
        }, ARRAY_FILTER_USE_BOTH);
        $data['restriction'] = $restrictedArray;
        return $data;
    }

    protected function setFamilyId($item)
    {
        $item['familyId'] = $this->config['departmentToFamily'][$item['departmentId']]['familyId'];
     //   unset($item['departmentId']);
        return $item;
    }
    protected function setProductType($item)
    {
        $item['productType'] = $this->config['departmentToProductType'][$item['departmentId']]['productType'];
        return $item;
    }
    protected function setProductCategory($item)
    {
        $item['productCategory'] = $this->config['departmentToCategory'][$item['departmentId']]['category'];
        return $item;
    }

}