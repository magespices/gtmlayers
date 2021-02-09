<?php
/**
 * Created by Q-Solutions Studio
 * Date: 09.07.2020
 *
 * @category    Magespices
 * @package     Magespices_GTMDataLayers
 * @author      Maciej Buchert <maciej@qsolutionsstudio.com>
 */

namespace Magespices\GTMDataLayers\Helper;

use Magento\Catalog\Helper\Data as ProductHelper;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product as ProductResourceModel;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Data
 * @package Magespices\GTMDataLayers\Helper
 */
class Data extends AbstractHelper
{
    /** @var string */
    public const GTMDATALAYERS_GENERAL_GTM_IDENTIFIER_XPATH = 'gtmdatalayers/general/gtm_identifier';

    /** @var string */
    public const GTMDATALAYERS_GENERAL_PRODUCT_IDENTIFIER_XPATH = 'gtmdatalayers/general/product_identifier';

    /** @var string */
    public const GTMDATALAYERS_GENERAL_PRODUCT_BRAND_XPATH = 'gtmdatalayers/general/product_brand';

    /** @var string */
    public const GTMDATALAYERS_GENERAL_AFFILIATION_XPATH = 'gtmdatalayers/general/affiliation';

    /** @var string */
    public const GTMDATALAYERS_LAYERS_PRODUCT_DETAILS_ENABLED_XPATH = 'gtmdatalayers/layers/product_details_enabled';

    /** @var string */
    public const GTMDATALAYERS_LAYERS_CART_OPERATIONS_ENABLED_XPATH = 'gtmdatalayers/layers/cart_operations_enabled';

    /** @var string */
    public const GTMDATALAYERS_LAYERS_PURCHASE_ENABLED_XPATH = 'gtmdatalayers/layers/purchase_enabled';

    /**
     * @var ProductHelper
     */
    protected $productHelper;

    /**
     * @var ProductFactory
     */
    protected $productFactory;

    /**
     * @var ProductResourceModel
     */
    protected $productResourceModel;

    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Data constructor.
     * @param Context $context
     * @param ProductHelper $productHelper
     * @param ProductFactory $productFactory
     * @param ProductResourceModel $productResourceModel
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        ProductHelper $productHelper,
        ProductFactory $productFactory,
        ProductResourceModel $productResourceModel,
        CategoryCollectionFactory $categoryCollectionFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->productHelper = $productHelper;
        $this->productFactory = $productFactory;
        $this->productResourceModel = $productResourceModel;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * @param string $path
     * @return mixed
     */
    public function getConfigValue(string $path)
    {
        return $this->scopeConfig->getValue($path);
    }

    /**
     * @return string|null
     */
    public function getGTMIdentifier(): ?string
    {
        return $this->getConfigValue(self::GTMDATALAYERS_GENERAL_GTM_IDENTIFIER_XPATH);
    }

    /**
     * @param int|null $productId
     * @return string
     */
    public function getProductIdentifier(int $productId = null): string
    {
        $attribute = $this->getConfigValue(self::GTMDATALAYERS_GENERAL_PRODUCT_IDENTIFIER_XPATH);
        $product = $this->getProduct($productId);

        if ($product) {
            if ($product->getCustomAttribute($attribute)) {
                return $product->getAttributeText($attribute);
            }
            return $product->getData($attribute);
        }
        return '';
    }

    /**
     * @return string
     */
    public function getProductBrandField(): string
    {
        return $this->getConfigValue(self::GTMDATALAYERS_GENERAL_PRODUCT_BRAND_XPATH);
    }

    /**
     * @param int|null $productId
     * @return Product|null
     */
    public function getProduct(int $productId = null): ?Product
    {
        if ($productId) {
            $product = $this->productFactory->create();
            $this->productResourceModel->load($product, $productId);
            return $product;
        }
        return $this->productHelper->getProduct();
    }

    /**
     * @param array $categoryIds
     * @param string $glue
     * @return string
     */
    public function getCategoriesAsString(array $categoryIds, string $glue = '|'): string
    {
        $categoryNames = [];
        $categoriesCollection = $this->categoryCollectionFactory->create();
        $categoriesCollection
            ->addFieldToFilter('entity_id', ['in' => $categoryIds])
            ->addFieldToSelect('name');

        /** @var Category $category */
        foreach ($categoriesCollection->getItems() as $category) {
            $categoryNames[] = $category->getName();
        }

        return implode($glue, $categoryNames);
    }

    public function getCurrencyCode(): string
    {
        return $this->storeManager->getStore()->getCurrentCurrencyCode();
    }
}
