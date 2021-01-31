<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\SchemaOrg;

use Magento\Catalog\Model\Product as ProductModel;
use Magento\Catalog\Model\ProductRepositoryFactory;
use Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\Image\Placeholder as PlaceholderProvider;
use Magento\CatalogUrlRewrite\Model\ProductUrlRewriteGenerator;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Paskel\Seo\Api\Data\SchemaOrgInterface;
use Paskel\Seo\Helper\Hreflang;
use Paskel\Seo\Helper\Url as UrlHelper;

/**
 * Class Product
 * @package Paskel\Seo\Model\SchemaOrg
 */
class Product implements SchemaOrgInterface
{
    const SCHEMA_TYPE = "Product";
    const OFFERS_AVAILABILITY_SCHEMA = "https://schema.org/InStock";

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @var ProductRepositoryFactory
     */
    protected ProductRepositoryFactory $productRepositoryFactory;

    /**
     * @var PlaceholderProvider
     */
    protected PlaceholderProvider $placeholderProvider;

    /**
     * @var Hreflang
     */
    protected Hreflang $hreflangHelper;

    /**
     * Product constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param ProductRepositoryFactory $productRepositoryFactory
     * @param Hreflang $hreflangHelper
     * @param PlaceholderProvider $placeholderProvider
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        ProductRepositoryFactory $productRepositoryFactory,
        Hreflang $hreflangHelper,
        PlaceholderProvider $placeholderProvider
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->productRepositoryFactory = $productRepositoryFactory;
        $this->hreflangHelper = $hreflangHelper;
        $this->placeholderProvider = $placeholderProvider;
    }

    /**
     * Returns the schema type.
     *
     * @return string
     */
    public function getType() {
        return self::SCHEMA_TYPE;
    }

    /**
     * Returns the script for a given product.
     *
     * @param $productId
     * @return string
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getScript($productId = null) {
        // check that this schema is enabled.
        if (!$this->isEnabled()) {
            return null;
        }
        if ($productId == null) {
            throw new LocalizedException(__("ERROR: No product id specified."));
        }
        $script = '<script type="application/ld+json">{';
        $properties = $this->getProperties($productId);
        foreach ($properties as $name=>$content) {
            if (!empty(ltrim(rtrim($content, '"'), '"'))) {
                $script .= '"' . $name . '": ' . $content . ',';
            }
        }
        return rtrim($script, ",")
            . '}'
            . '</script>';
    }

    /**
     * Returns schema properties wrapped in an associative array.
     *
     * @param $productId
     * @return string[]
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getProperties($productId) {
        // retrieve product
        if ($productId == null) {
            throw new LocalizedException(__("ERROR: No product id specified."));
        }
        $productRepository = $this->productRepositoryFactory->create();
        $product = $productRepository->getById($productId);

        // retrieve store
        $store = $this->storeManager->getStore();

        return [
            '@context' => '"' . $this->getSchemaContext() . '"',
            '@type' => '"' . $this->getSchemaType() . '"',
            'name' => '"' . addslashes($this->getName($product)) . '"',
            'image' => '"' . $this->getImageUrl($product, $store) . '"',
            'description' => '"' . addslashes($this->getDescription($product)) . '"',
            'mpn' => '"' . addslashes($this->getMpn($product)) . '"',
            'offers' => $this->getOffers($product, $store)
        ];
    }

    /**
     * Return if the schema.org has been enabled in the config.
     *
     * @param null $storeId
     * @return mixed
     */
    public function isEnabled($storeId = null) {
        return $this->scopeConfig->getValue(
            'seo/schemaOrg/enable_product',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Returns the schema context
     *
     * @return string
     */
    protected function getSchemaContext() {
        return self::SCHEMA_CONTEXT;
    }

    /**
     * Returns the schema type
     *
     * @return string
     */
    protected function getSchemaType() {
        return self::SCHEMA_TYPE;
    }

    /**
     * Retrieve product name.
     *
     * @param ProductModel $product
     * @return string
     */
    protected function getName($product) {
        return $product->getName();
    }

    /**
     * Retrieve product image url.
     * If not, use placeholder image.
     *
     * @param ProductModel $product
     * @param StoreInterface $store
     * @return string|null
     */
    protected function getImageUrl($product, $store) {
        // retrieve store info
        $storeUrl = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        $imageUrl = $product->getImage();

        if (!empty($imageUrl)) {
            return UrlHelper::pinchUrl($storeUrl . 'catalog/product', $imageUrl);
        } else {
            return $this->placeholderProvider->getPlaceholder('small_image');
        }
    }

    /**
     * Retrieve product meta description.
     *
     * @param ProductModel $product
     * @return string
     */
    protected function getDescription($product) {
        return $product->getMetaDescription() ?? $product->getDescription();
    }

    /**
     * Retrieve product mpn.
     *
     * @param ProductModel $product
     * @return string
     */
    protected function getMpn($product) {
        return $product->getSku();
    }

    /**
     * Retrieve product offers.
     *
     * @param ProductModel $product
     * @param $store
     * @return string
     * @throws LocalizedException
     */
    protected function getOffers($product, $store) {
        // add tag properties
        $properties['@type'] = 'Offer';
        $properties['price'] = number_format($product->getPrice(), 2);
        $properties['priceCurrency'] = $store->getCurrentCurrencyCode();
        $properties['availability'] = self::OFFERS_AVAILABILITY_SCHEMA;
        $hreflang = $this->hreflangHelper->getStoreHreflang(
            $product->getId(),
            ProductUrlRewriteGenerator::ENTITY_TYPE,
            $store->getId()
        );
        if ($hreflang) {
            $properties['url'] = $hreflang->getUrl();
        }
        // create tag based on available tag properties
        $tag = '[{';
        foreach ($properties as $name=>$content) {
            if (!empty(ltrim(rtrim($content, '"'), '"'))) {
                $tag .= '"' . $name . '": "' . $content . '",';
            }
        }
        return rtrim($tag, ",") . '}]';
    }
}