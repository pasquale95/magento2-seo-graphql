<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\Product;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Url as MagentoUrl;
use Magento\CatalogUrlRewrite\Model\ProductUrlRewriteGenerator;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Session\SidResolverInterface;
use Magento\Framework\UrlFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Product Url model.
 * Overwritten for a bug in the getUrl() function which
 * does not allow to get the product url with the category.
 *
 * Class Url
 * @package Paskel\Seo\Model\Product
 */
class Url extends MagentoUrl
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param UrlFactory $urlFactory
     * @param StoreManagerInterface $storeManager
     * @param FilterManager $filter
     * @param SidResolverInterface $sidResolver
     * @param UrlFinderInterface $urlFinder
     * @param array $data
     * @param ScopeConfigInterface|null $scopeConfig
     */
    public function __construct(
        UrlFactory $urlFactory,
        StoreManagerInterface $storeManager,
        FilterManager $filter,
        SidResolverInterface $sidResolver,
        UrlFinderInterface $urlFinder,
        array $data = [],
        ScopeConfigInterface $scopeConfig = null
    ) {
        parent::__construct(
            $urlFactory,
            $storeManager,
            $filter,
            $sidResolver,
            $urlFinder,
            $data,
            $scopeConfig
        );
        $this->scopeConfig = $scopeConfig ?: ObjectManager::getInstance()->get(ScopeConfigInterface::class);
    }


    /**
     * Retrieve Product URL using UrlDataObject
     *
     * @param Product $product
     * @param array $params
     * @return string
     * @throws NoSuchEntityException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getUrl(Product $product, $params = [])
    {
        $routePath = '';
        $routeParams = $params;

        $storeId = $product->getStoreId();

        $categoryIds = $product->getCategoryIds();
        $categoryId = null;

        // START fix
        if (!isset($params['_ignore_category'])) {
            $categoryId = $categoryIds ? max($categoryIds) : null;
        }
        // END fix

        if ($product->hasUrlDataObject()) {
            $requestPath = $product->getUrlDataObject()->getUrlRewrite();
            $routeParams['_scope'] = $product->getUrlDataObject()->getStoreId();
        } else {
            $requestPath = $product->getRequestPath();
            if (empty($requestPath) && $requestPath !== false) {
                $filterData = [
                    UrlRewrite::ENTITY_ID => $product->getId(),
                    UrlRewrite::ENTITY_TYPE => ProductUrlRewriteGenerator::ENTITY_TYPE,
                    UrlRewrite::STORE_ID => $storeId,
                ];

                $useCategories = $this->scopeConfig->getValue(
                    \Magento\Catalog\Helper\Product::XML_PATH_PRODUCT_URL_USE_CATEGORY,
                    ScopeInterface::SCOPE_STORE
                );

                $filterData[UrlRewrite::METADATA]['category_id']
                    = $categoryId && $useCategories ? $categoryId : '';

                $rewrite = $this->urlFinder->findOneByData($filterData);

                if ($rewrite) {
                    $requestPath = $rewrite->getRequestPath();
                    $product->setRequestPath($requestPath);
                } else {
                    $product->setRequestPath(false);
                }
            }
        }

        if (isset($routeParams['_scope'])) {
            $storeId = $this->storeManager->getStore($routeParams['_scope'])->getId();
        }

        if ($storeId != $this->storeManager->getStore()->getId()) {
            $routeParams['_scope_to_url'] = true;
        }

        if (!empty($requestPath)) {
            $routeParams['_direct'] = $requestPath;
        } else {
            $routePath = 'catalog/product/view';
            $routeParams['id'] = $product->getId();
            $routeParams['s'] = $product->getUrlKey();
            if ($categoryId) {
                $routeParams['category'] = $categoryId;
            }
        }

        // reset cached URL instance GET query params
        if (!isset($routeParams['_query'])) {
            $routeParams['_query'] = [];
        }

        $url = $this->urlFactory->create()->setScope($storeId);
        return $url->getUrl($routePath, $routeParams);
    }
}
