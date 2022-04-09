<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Shopby
 */


namespace Amasty\Shopby\Model\Layer\Filter;

use Magento\Framework\Exception\StateException;
use Magento\Search\Api\SearchInterface;
use Amasty\Shopby\Model\Layer\Filter\OnSale\Helper;
use Amasty\Shopby\Model\Layer\Filter\Traits\CustomTrait;
use Magento\Store\Model\ScopeInterface;

class OnSale extends \Magento\Catalog\Model\Layer\Filter\AbstractFilter
{
    use CustomTrait;

    const FILTER_ON_SALE = 1;

    const ATTRIBUTE_CODE = 'am_on_sale';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Amasty\Shopby\Model\Request
     */
    private $shopbyRequest;

    /**
     * @var Helper
     */
    private $helper;

    /**
     * @var SearchInterface
     */
    private $search;

    /**
     * @var \Amasty\Shopby\Helper\FilterSetting
     */
    private $settingHelper;

    public function __construct(
        \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer $layer,
        \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Amasty\Shopby\Model\Request $shopbyRequest,
        Helper $helper,
        \Amasty\Shopby\Helper\FilterSetting $settingHelper,
        SearchInterface $search,
        array $data = []
    ) {
        parent::__construct(
            $filterItemFactory,
            $storeManager,
            $layer,
            $itemDataBuilder,
            $data
        );
        $this->settingHelper = $settingHelper;
        $this->_requestVar = 'am_on_sale';
        $this->scopeConfig = $scopeConfig;
        $this->shopbyRequest = $shopbyRequest;
        $this->helper = $helper;
        $this->search = $search;
    }

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     *
     * @return $this
     */
    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        if ($this->isApplied()) {
            return $this;
        }

        $filter = $this->shopbyRequest->getFilterParam($this);
        if (!in_array($filter, [self::FILTER_ON_SALE])) {
            return $this;
        }

        $this->setCurrentValue($filter);
        if ($filter == self::FILTER_ON_SALE) {
            $this->getLayer()->getProductCollection()->addFieldToFilter($this->getAttributeCode(), $filter);
            $name = __('Yes');
            $this->getLayer()->getState()->addFilter($this->_createItem($name, $filter));
        }

        return $this;
    }

    /**
     * Get filter name
     *
     * @return \Magento\Framework\Phrase
     */
    public function getName()
    {
        $label = $this->scopeConfig
            ->getValue('amshopby/am_on_sale_filter/label', ScopeInterface::SCOPE_STORE);
        return $label;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        $position = (int) $this->scopeConfig
            ->getValue('amshopby/am_on_sale_filter/position', ScopeInterface::SCOPE_STORE);
        return $position;
    }

    /**
     * Get data array for building category filter items
     *
     * @return array
     */
    protected function _getItemsData()
    {
        if ($this->isHide()) {
            return [];
        }

        try {
            $optionsFacetedData = $this->getFacetedData();
        } catch (StateException $e) {
            $optionsFacetedData = [];
        }

        $isOnSale = isset($optionsFacetedData[1]) ? $optionsFacetedData[1]['count'] : 0;

        $listData = [
            [
                'label' => __('On Sale'),
                'value' => self::FILTER_ON_SALE,
                'count' => $isOnSale,
            ]
        ];

        foreach ($listData as $data) {
            if ($data['count'] < 1) {
                continue;
            }
            $this->itemDataBuilder->addItemData(
                $data['label'],
                $data['value'],
                $data['count']
            );
        }

        return $this->itemDataBuilder->build();
    }

    private function getAttributeCode(): ?string
    {
        return self::ATTRIBUTE_CODE;
    }
}
