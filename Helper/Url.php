<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Helper;

use Magento\Catalog\Helper\Product;
use Magento\CatalogUrlRewrite\Model\ProductUrlRewriteGenerator;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

/**
 * Class Url
 * @package Paskel\Seo\Helper
 */
class Url extends AbstractHelper
{
    /**
     * @var UrlFinderInterface
     */
    protected $urlFinder;

    /**
     * Category constructor.
     * @param Context $context
     * @param UrlFinderInterface $urlFinder
     */
    public function __construct(
        Context $context,
        UrlFinderInterface $urlFinder
    ) {
        $this->urlFinder = $urlFinder;
        parent::__construct($context);
    }

    /**
     * @param $domain
     * @param $path
     * @return string
     */
    public static function pinchUrl($domain, $path) {
        // if no path, just return back the domain
        if (!isset($path)) {
            return $domain;
        }
        // handle special case for homepage (reachable without path):
        // <domain>/home must become <domain>
        if ($path == 'home') {
            $path = "";
        }
        return rtrim($domain, "/") . "/" . ltrim($path, "/");
    }

    /**
     * Returns the requestPath as translated by url rewriter
     *
     * @param $entityId
     * @param $entityType
     * @param $storeId
     * @param int|null $categoryId
     * @return string|null
     */
    public function getRequestPathByUrlRewriter($entityId, $entityType, $storeId, $categoryId = null) {
        $filterData = [
            UrlRewrite::ENTITY_ID => $entityId,
            UrlRewrite::ENTITY_TYPE => $entityType,
            UrlRewrite::STORE_ID => $storeId,
        ];
        if ($entityType == ProductUrlRewriteGenerator::ENTITY_TYPE) {
            // add categoryId for product urls, if defined and set configuration at BO
            $useCategories = $this->scopeConfig->getValue(
                Product::XML_PATH_PRODUCT_URL_USE_CATEGORY,
                ScopeInterface::SCOPE_STORE
            );
            if ($categoryId and $useCategories) {
                $filterData[UrlRewrite::METADATA] = "{\"category_id\":\"$categoryId\"}";
            }
        }
        $rewrite = $this->urlFinder->findOneByData($filterData);
        if ($rewrite) {
            return $rewrite->getRequestPath();
        }
        return null;
    }
}
