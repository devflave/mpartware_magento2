<?php

namespace G3\ModuleSwatch\Plugin\Magento\ConfigurableProduct\Block\Product\View\Type;

class Configurable
{
    public function afterGetJsonConfig(\Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject, $result)
    {
        $jsonResult = json_decode($result, true);
        foreach ($subject->getAllowProducts() as $simpleProduct) {
            $id = $simpleProduct->getId();
            foreach ($simpleProduct->getAttributes() as $attribute) {
                if (($attribute->getIsVisible() && $attribute->getIsVisibleOnFront()) || in_array($attribute->getAttributeCode(), ['sku', 'description', 'name', 'sub1de', 'sub2de', 'price', 'pricebrutto'])) { // <= Here you can put any attribute you want to see dynamic
                    $code = $attribute->getAttributeCode();
                    $value = (string)$attribute->getFrontend()->getValue($simpleProduct);
                    $jsonResult['dynamic'][$code][$id] = [
                        'value' => $value
                    ];
                }
            }
        }
        $result = json_encode($jsonResult);


        return $result;
    }
}
