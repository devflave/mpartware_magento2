<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_ShopbyBase
 */


namespace Amasty\ShopbyBase\Api\Data;

use Magento\Framework\Exception\NoSuchEntityException;

interface FilterSettingRepositoryInterface
{
    const TABLE = 'amasty_amshopby_filter_setting';

    /**
     * @param int $id
     * @param null $idFieldName
     * @return FilterSettingInterface
     * @throws NoSuchEntityException
     */
    public function get($id, $idFieldName = null);

    /**
     * @param FilterSettingInterface $filterSetting
     * @return FilterSettingRepositoryInterface
     */
    public function save(FilterSettingInterface $filterSetting);
}
