<?php
/**
 * WebkulMarketplace
 *
 * @copyright Copyright © 2018 Firebear Studio. All rights reserved.
 * @author    Firebear Studio <fbeardev@gmail.com>
 */

namespace Firebear\ImportExport\Model\Import\Product\Integration;

use Exception;
use Firebear\ImportExport\Model\Import\Product;
use Webkul\Marketplace\Model\ResourceModel\Product\CollectionFactory as ProductCollection;
use Webkul\Marketplace\Helper\Data as MpHelper;
use Magento\Catalog\Model\ProductFactory as ProductModel;
use Webkul\Marketplace\Model\ProductFactory as MpProductFactory;

/**
 * Class WebkulMarketplaceAssignProduct
 * @package Firebear\ImportExport\Model\Import\Product\Integration
 */
class WebkulMarketplaceAssignProduct
{
    const SELLER_APROVED_CODE = 2;

    /**
     * @var sellerProductIds
     */
    protected $sellerProductIds;

    /**
     * @var sellerProductCollection
     */
    protected $sellerProductCollection;

    /**
     * @var sellers
     */
    protected $sellers;

    /**
     * @var mpHelper
     */
    protected $mpHelper;

    /**
     * @var mpProductFactory
     */
    protected $mpProductFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * @var ProductModel
     */
    protected $productModel;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $_objectManager;

    /**
     * WebkulMarketplaceAssignProduct constructor.
     * @param ProductModel $productModelFactory
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Framework\ObjectManagerInterface $objectmanager
     */
    public function __construct(
        ProductModel $productModelFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\ObjectManagerInterface $objectmanager
    ) {
        $this->productModel = $productModelFactory;
        $this->_date = $date;
        $this->_objectManager = $objectmanager;
    }

    /**
     * @param $sellerId
     * @return mixed
     */
    public function getSellerProductIds($sellerId)
    {
        if (empty($this->sellerProductIds[$sellerId]) && $this->sellerProductIds[$sellerId]==null) {
            $this->sellerProductIds[$sellerId] = $this->getSellerProductCollection()->create()->getAllAssignProducts(
                '`seller_id`=' . $sellerId
            );
        }

        return $this->sellerProductIds[$sellerId];
    }

    /**
     * @return mixed
     */
    public function getSellers()
    {
        if ($this->sellers == null) {
            $sellers = $this->getMpHelper()->getSellerList();
            if ($sellers) {
                foreach ($sellers as $value) {
                    if (isset($value['value']) && !empty($value['value'])) {
                        $this->sellers[] =  $value['value'];
                    }
                }
            }
        }

        return $this->sellers;
    }

    /**
     * @param $sellerId
     * @param $productIds
     * @return array
     */
    public function assignProduct($sellerId, $productIds)
    {
        $message = [];
        $sellers = $this->getSellers();
        if (!in_array($sellerId, $sellers)) {
            $message[] = "Seller with ID ". $sellerId." could not find.";
            return $message;
        } else {
            $sellerProducts = $this->getSellerProductIds($sellerId);
            $additionalProductIds = array_diff(array_values($productIds), array_values($sellerProducts));
            if (!$additionalProductIds) {
                $message[] = "No change for this bunch of products already assigned to seller.";
            }
            $helper = $this->getMpHelper();
            $allowedProductTypeIds = explode(',', $helper->getAllowedProductType());
            // set product status to 1 to assign selected products from seller
            $productCollection = $this->productModel->create()
                ->getCollection()
                ->addFieldToFilter(
                    'entity_id',
                    ['in' => $additionalProductIds]
                )->addFieldToFilter(
                    'type_id',
                    ['in' => $allowedProductTypeIds]
                );
            $successMessage = '';
            foreach ($productCollection as $product) {
                $proId = $product->getId();
                $sku = $product->getSku();
                $collection = $this->getSellerProductCollection()->create()
                    ->addFieldToFilter(
                        'mageproduct_id',
                        $proId
                    );
                $flag = !$collection->count();
                foreach ($collection as $coll) {
                    if ($sellerId != $coll['seller_id']) {
                        $coll->setSellerId($sellerId);
                        $coll->setAdminassign(1)->save();
                    } else {
                        $coll->setAdminassign(1)->save();
                    }
                }
                if ($flag) {
                    $collectionMpProduct = $this->getMpProductFactory()->create();
                    $collectionMpProduct->setMageproductId($proId);
                    $collectionMpProduct->setMageProRowId($proId);
                    $collectionMpProduct->setSellerId($sellerId);
                    $collectionMpProduct->setStatus($product->getStatus());
                    $collectionMpProduct->setAdminassign(1);
                    $isApproved = 1;
                    if ($product->getStatus() == self::SELLER_APROVED_CODE && $helper->getIsProductApproval()) {
                        $isApproved = 0;
                    }
                    $collectionMpProduct->setIsApproved($isApproved);
                    $collectionMpProduct->setCreatedAt($this->_date->gmtDate());
                    $collectionMpProduct->setUpdatedAt($this->_date->gmtDate());
                    $collectionMpProduct->save();
                    array_push($this->sellerProductIds[$sellerId], $proId);
                    $message[$proId] = "Product ".$sku." assigned seccuessfully to the Seller.";
                }
            }
            return $message;
        }
    }

    /**
     * @return sellerProductCollection|mixed
     */
    public function getSellerProductCollection()
    {
        if ($this->sellerProductCollection == null) {
            $this->sellerProductCollection = $this->_objectManager->get(ProductCollection::class);
        }
        return $this->sellerProductCollection;
    }

    /**
     * @return mixed|MpHelper
     */
    public function getMpHelper()
    {
        if ($this->mpHelper == null) {
            $this->mpHelper = $this->_objectManager->get(MpHelper::class);
        }
        return $this->mpHelper;
    }

    /**
     * @return mixed|MpProductFactory
     */
    public function getMpProductFactory()
    {
        if ($this->mpProductFactory == null) {
            $this->mpProductFactory = $this->_objectManager->get(MpProductFactory::class);
        }
        return $this->mpProductFactory;
    }
}
