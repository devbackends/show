<?php

namespace Webkul\Shipping\Carriers;

abstract class AbstractShipping
{
    abstract public function calculate($items = []);

    /**
     * Checks if payment method is available
     *
     * @return array
     */
    public function isAvailable()
    {
        return $this->getConfigData('active');
    }

    /**
     * Returns payment method code
     *
     * @return array
     */
    public function getCode()
    {
        if (empty($this->code)) {
            // throw exception
        }

        return $this->code;
    }

    /**
     * Returns payment method title
     *
     * @return array
     */
    public function getTitle()
    {
        return $this->getConfigData('title');
    }

    /**
     * Returns payment method decription
     *
     * @return array
     */
    public function getDescription()
    {
        return $this->getConfigData('decription');
    }

    /**
     * Retrieve information from payment configuration
     *
     * @param $field
     * @return mixed
     */
    public function getConfigData($field)
    {
        return core()->getConfigData('sales.carriers.' . $this->getCode() . '.' . $field);
    }

    /**
     * @param array $items
     * @return int[]
     */
    protected function calculateTotals(array $items): array
    {
        $totals = [
            'weight' => 0,
            'length' => 0,
            'width' => 0,
            'height' => 0,
        ];

        foreach ($items as $item) {
            if ($item->type === 'virtual' || $item->type === 'booking') continue;

            $weight = (float)$item->product['weight'];
            if (!$weight > 0) {
                $weight = (float)$item->product['weight_lbs'];
            } else {
                $weight = $weight / 16; // convert to pounds
            }

            $totals['weight'] += $weight; // in pounds
            if(is_numeric($item->product['width'])){
                $totals['width'] += $item->product['width'];
            }
            if(is_numeric($item->product['height'])){
                $totals['height'] += $item->product['height'];
            }
            $length = $item->product['depth'];
            if ($length > $totals['length']) {
                $totals['length'] = (float)$length;
            }
        }

        // Multiply width
        $totals['width'] = round($totals['width'] * 0.75, 2); // TODO QUESTION

        return $totals;
    }
}