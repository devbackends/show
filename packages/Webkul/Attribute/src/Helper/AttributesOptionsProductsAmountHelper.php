<?php

namespace Webkul\Attribute\Helper;

use Webkul\Attribute\Models\AttributesOptionsProductsAmount;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;

class AttributesOptionsProductsAmountHelper
{

    /**
     * @var AttributesOptionsProductsAmount
     */
    protected $model;

    /**
     * @var Product
     */
    protected $product;

    protected $requestData = [];

    /**
     * AttributesOptionsProductsAmountHelper constructor.
     *
     * @param Product $product
     * @param array $requestData
     */
    public function __construct(Product $product, array $requestData)
    {
        $this->model = app(AttributesOptionsProductsAmount::class);

        $this->product = $product;
        $this->requestData = $requestData;
    }

    /**
     * The main method that execute all helper logic
     *
     * @return bool
     */
    public function execute()
    {
        $attributesFromRequest = $this->getAttributesFromRequest();
        $attributesFromProduct = $this->getAttributesFromProduct();

        $result = $this->handleCategoriesChanging();
        if (!$result) return false;

        // Increase products amount for new categories
        if (!empty($result['new'])) {
            $this->increaseProductAmountForNewCategories($attributesFromRequest, $result['new']);
        }

        // Implement actions (increase/decrease) to options of already existing categories
        if (!empty($result['existing'])) {
            $this->actionsToProductAmountForExistingCategories($attributesFromProduct, $attributesFromRequest, $result['existing']);
        }

        // Decrease products amount for removed categories
        if (!empty($result['removed'])) {
            $this->decreaseProductAmountForRemovedCategories($attributesFromProduct, $result['removed']);
        }

        return true;
    }

    /**
     * Generates bind beetwen attribute and selected option from request data
     *
     * @return array
     */
    protected function getAttributesFromRequest()
    {
        $attributes = [];

        $newCategories = $this->requestData['categories'] ?? [];
        foreach ($newCategories as $newCategoryId) {
            $newCategory = app(CategoryRepository::class)->find($newCategoryId);
            $filterableAttrs = $newCategory->filterableAttributes;

            $attributes[$newCategoryId] = [];
            foreach ($filterableAttrs as $filterableAttr) {
                if ($filterableAttr->code === 'price') continue;
                if (isset($this->requestData[$filterableAttr->code]) && !empty($this->requestData[$filterableAttr->code])) {
                    if ($filterableAttr->type === 'multiselect') {
                        if(is_array($this->requestData[$filterableAttr->code])){
                            $attributes[$newCategoryId][$filterableAttr->id] = array_map(function ($v) {return (int)$v;}, $this->requestData[$filterableAttr->code]);
                        }else{
                            $attributes[$newCategoryId][$filterableAttr->id] = array_map(function ($v) {return (int)$v;}, explode(",", $this->requestData[$filterableAttr->code]));
                        }
                    } else {
                        $attributes[$newCategoryId][$filterableAttr->id] = (int)$this->requestData[$filterableAttr->code];;
                    }
                }
            }
        }

        return $attributes;
    }

    /**
     * Generates bind beetwen attribute and existing choosed option from db data
     *
     * @return array
     */
    protected function getAttributesFromProduct()
    {
        $attributes = [];

        $oldCategories = $this->product->categories->pluck('id')->toArray() ?? [];
        foreach ($oldCategories as $oldCategoryId) {
            $newCategory = app(CategoryRepository::class)->find($oldCategoryId);
            $filterableAttrs = $newCategory->filterableAttributes;

            $attributes[$oldCategoryId] = [];
            $values = $this->product->attribute_values()->get();
            foreach ($filterableAttrs as $filterableAttr) {
                if ($filterableAttr->code === 'price') continue;

                $value = $values->firstWhere('attribute_id', $filterableAttr->id);
                if ($value && $value->{ProductAttributeValue::$attributeTypeFields[$filterableAttr['type']]}) {
                    if ($filterableAttr['type'] === 'multiselect') {
                        $options = explode(',', $value->{ProductAttributeValue::$attributeTypeFields[$filterableAttr['type']]});
                        $attributes[$oldCategoryId][$filterableAttr->id] = array_map(function ($v) {return (int)$v;}, $options);
                    } else {
                        $attributes[$oldCategoryId][$filterableAttr->id] = (int)$value->{ProductAttributeValue::$attributeTypeFields[$filterableAttr['type']]};
                    }

                }

            }
        }

        return $attributes;
    }

    /**
     * Detect which categories should be added as new, parsed as existing, or removed as old
     *
     * @return array[]|false
     */
    protected function handleCategoriesChanging()
    {
        $oldCategories = $this->product->categories->pluck('id')->toArray();
        $newCategories = $this->requestData['categories'] ?? [];

        // Sort categories to compare them correctly
        sort($oldCategories);
        sort($newCategories);

        // Crate result template
        $result = [
            'new' => [],
            'existing' => [],
            'removed' => [],
        ];

        // Compare categories
        if (empty($oldCategories) && empty($newCategories)) return false;

        if (!empty($oldCategories) && empty($newCategories)) {
            $result['removed'] = $oldCategories;
        }

        if (empty($oldCategories) && !empty($newCategories)) {
            $result['new'] = $newCategories;
        }

        if (!empty($oldCategories) && !empty($newCategories)) {
            if ($oldCategories === $newCategories) {
                $result['existing'] = $oldCategories;
            } else {
                foreach ($newCategories as $newCategory) {
                    if (in_array($newCategory, $oldCategories)) {
                        array_push($result['existing'], $newCategory);
                    } else {
                        array_push($result['new'], $newCategory);
                    }
                }
                foreach ($oldCategories as $oldCategory) {
                    if (!in_array($oldCategory, $newCategories)) {
                        array_push($result['removed'], $oldCategory);
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Increase attribute option product amount by new categories
     *
     * @param array $attributes
     * @param array $categories
     */
    protected function increaseProductAmountForNewCategories(array $attributes, array $categories)
    {
        foreach ($categories as $categoryId) {
            if($categoryId!=64){
                $options = [];
                foreach ($attributes[$categoryId] as $value) {
                    if (is_array($value)) {
                        $options = array_merge($options, $value);
                    } else {
                        array_push($options, $value);
                    }
                }

                if (!empty($options)) {
                    $this->model->increaseByCategory($categoryId, $options);
                }
            }
        }
    }

    /**
     * Increase/decrease existing attribute option product amount by existing categories
     *
     * @param array $productAttrs
     * @param array $requestAttrs
     * @param array $categories
     */
    protected function actionsToProductAmountForExistingCategories(array $productAttrs, array $requestAttrs, array $categories)
    {
        foreach ($categories as $categoryId) {
            $optionsToIncrease = [];
            $optionsToDecrease = [];

            $productAttrsByCategory = $productAttrs[$categoryId];
            $requestAttrsByCategory = $requestAttrs[$categoryId];

            foreach ($productAttrsByCategory as $productAttrId => $option) {
                if (!isset($requestAttrsByCategory[$productAttrId])) {
                    if (is_array($option)) {
                        $optionsToDecrease = array_merge($optionsToDecrease, $option);
                    } else {
                        array_push($optionsToDecrease, $option);
                    }
                } else {
                    if ($requestAttrsByCategory[$productAttrId] !== $option) {
                        if (is_array($requestAttrsByCategory[$productAttrId])) {
                            foreach ($requestAttrsByCategory[$productAttrId] as $requestAttrOption) {
                                if (!in_array($requestAttrOption, $option)) {
                                    array_push($optionsToIncrease, $requestAttrOption);
                                }
                            }
                            foreach ($option as $optionValue) {
                                if (!in_array($optionValue, $requestAttrsByCategory[$productAttrId])) {
                                    array_push($optionsToDecrease, $optionValue);
                                }
                            }
                        } else {
                            array_push($optionsToDecrease, $option);
                            array_push($optionsToIncrease, $requestAttrsByCategory[$productAttrId]);
                        }
                    }
                }
            }
            foreach ($requestAttrsByCategory as $requestAttrId => $option) {
                if (!isset($productAttrsByCategory[$requestAttrId])) {
                    if (is_array($option)) {
                        $optionsToIncrease = array_merge($optionsToIncrease, $option);
                    } else {
                        array_push($optionsToIncrease, $option);
                    }
                }
            }

            if (!empty($optionsToIncrease)) {
                $this->model->increaseByCategory($categoryId, $optionsToIncrease);
            }
            if (!empty($optionsToDecrease)) {
                $this->model->decreaseByCategory($categoryId, $optionsToDecrease);
            }
        }
    }

    /**
     * Decrease attribute option product amount by old categories
     *
     * @param array $attributes
     * @param array $categories
     */
    protected function decreaseProductAmountForRemovedCategories(array $attributes, array $categories)
    {
        foreach ($categories as $categoryId) {
            $options = [];
            foreach ($attributes[$categoryId] as $value) {
                if (is_array($value)) {
                    $options = array_merge($options, $value);
                } else {
                    array_push($options, $value);
                }
            }

            if (!empty($options)) {
                $this->model->decreaseByCategory($categoryId, $options);
            }
        }
    }



}