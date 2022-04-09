<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_ShopbyBase
 */


namespace Amasty\ShopbyBase\Model\ResourceModel;

use Amasty\ShopbyBase\Api\Data\FilterSettingInterface;
use Amasty\ShopbyBase\Api\Data\FilterSettingRepositoryInterface;
use Magento\Framework\Model\AbstractModel;

class FilterSetting extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * FilterSetting protected constructor
     */
    protected function _construct()
    {
        $this->_init(FilterSettingRepositoryInterface::TABLE, FilterSettingInterface::FILTER_SETTING_ID);
    }

    /**
     * @param AbstractModel $object
     * @return $this
     */
    protected function _afterLoad(AbstractModel $object)
    {
        if ($object->getId()) {
            $this->lookupCategoriesFilter($object);
            $this->lookupAttributesFilter($object);
            $this->lookupAttributesOptionsFilter($object);
        }
        return parent::_afterLoad($object);
    }

    /**
     * @param AbstractModel $object
     */
    public function lookupCategoriesFilter(AbstractModel $object)
    {
        $categoriesFilter = $object->getData('categories_filter');
        if (!is_array($categoriesFilter)) {
            $categoriesFilterArray = [];
            if ($categoriesFilter !== '' && $categoriesFilter !== null) {
                $categoriesFilterArray = explode(',', $categoriesFilter);
            }
            $object->setData('categories_filter', $categoriesFilterArray);
        }
    }

    /**
     * @param AbstractModel $object
     */
    public function lookupAttributesFilter(AbstractModel $object)
    {
        $attributesFilter = $object->getData('attributes_filter');
        if (!is_array($attributesFilter)) {
            $attributesFilterArray = [];

            if ($attributesFilter !== '' && $attributesFilter !== null) {
                $attributesFilterArray = explode(',', $attributesFilter);
            }
            $object->setData('attributes_filter', $attributesFilterArray);
        }
    }

    /**
     * @param AbstractModel $object
     */
    public function lookupAttributesOptionsFilter(AbstractModel $object)
    {
        $attributesOptionsFilter = $object->getData('attributes_options_filter');

        if (!is_array($attributesOptionsFilter)) {
            $attributesOptionsFilterArray = [];
            if ($attributesOptionsFilter !== '' && $attributesOptionsFilter !== null) {
                $attributesOptionsFilterArray = explode(',', $attributesOptionsFilter);
            }
            $object->setData('attributes_options_filter', $attributesOptionsFilterArray);
        }
    }
}
