<?php

namespace Webkul\RMA\Helpers;

class Helper
{
    public function getOptionDetailHtml($attributes)
    {
        $attributeValue = '';
        foreach ($attributes as $attribute) {
            if ($attributeValue != "") {
                $attributeValue = $attributeValue . "-" . $attribute['option_label'];
            } else {
                $attributeValue = $attribute['option_label'];
            }
        }

        return $attributeValue != '' ? "($attributeValue)" : "";
    }
}
