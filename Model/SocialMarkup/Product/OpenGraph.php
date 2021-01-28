<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\SocialMarkup\Product;

use Magento\CatalogUrlRewrite\Model\ProductUrlRewriteGenerator;
use Magento\StoreGraphQl\Model\Resolver\Store\StoreConfigDataProvider;
use Magento\Catalog\Model\Product;
use Paskel\Seo\Api\Data\OpenGraphInterface;
use Paskel\Seo\Helper\SocialMarkup as SocialMarkupHelper;
use Paskel\Seo\Helper\Hreflang as HreflangHelper;

/**
 * Class OpenGraph
 * @package Paskel\Seo\Model\SocialMarkup\Product
 */
class OpenGraph extends AbstractSocialMarkup implements OpenGraphInterface
{
    /**
     * @var StoreConfigDataProvider
     */
    protected StoreConfigDataProvider $storeConfigDataProvider;

    /**
     * @var HreflangHelper
     */
    protected HreflangHelper $hreflangHelper;

    /**
     * AbstractSocialMarkup constructor.
     *
     * @param SocialMarkupHelper $socialMarkupHelper
     * @param StoreConfigDataProvider $storeConfigsDataProvider
     * @param HreflangHelper $hreflangHelper
     */
    public function __construct(
        SocialMarkupHelper $socialMarkupHelper,
        StoreConfigDataProvider $storeConfigsDataProvider,
        HreflangHelper $hreflangHelper
    ) {
        $this->storeConfigDataProvider = $storeConfigsDataProvider;
        $this->hreflangHelper = $hreflangHelper;
        parent::__construct($socialMarkupHelper);
    }

    /**
     * Retrieve OpenGraph tags
     *
     * @param Product $item
     * @param $store
     * @return array
     */
    public function getTags($item, $store) {
        $storeId = $store->getId();
        // add tags
        $tags = [
            self::TYPE => $this->getType(),
            self::LOCALE => $this->getLocale($store),
            self::SITE => $this->getSitename($storeId),
            self::URL => $this->getUrl($item, $storeId),
            self::TITLE => $this->getTitle($item),
            self::DESCRIPTION => $this->getDescription($item),
            self::IMAGE => $this->getImage($item, $store)
        ];
        // remove unset properties if requested by user
        if ($this->socialMarkupHelper->hideUnsetPropertiesInGraphQl()) {
            return $this->removeUnsetTags($tags);
        }
        return $tags;
    }

    /**
     * Unset all array elements with an empty value.
     *
     * @param $tags
     * @return mixed
     */
    protected function removeUnsetTags($tags) {
        foreach ($tags as $property=>$content) {
            if (empty($content)) {
                unset($tags[$property]);
            }
        }
        return $tags;
    }

    /**
     * Return OpenGraph website type.
     *
     * @return string
     */
    public function getType() {
        return self::TYPE_VALUE;
    }

    /**
     * Return store locale.
     *
     * @param $store
     * @return string
     */
    public function getLocale($store) {
        return $this->storeConfigDataProvider->getStoreConfigData($store)['locale'];
    }

    /**
     * Returns site name.
     *
     * @param $storeId
     * @return mixed|null
     */
    protected function getSitename($storeId) {
        return $this->socialMarkupHelper->getSitename($storeId) ?? null;
    }

    /**
     * Return product url.
     *
     * @param Product $product
     * @param $storeId
     * @return string|null
     */
    protected function getUrl($product, $storeId) {
        return $this->socialMarkupHelper->retrieveUrl(
            $product->getId(),
            ProductUrlRewriteGenerator::ENTITY_TYPE,
            $storeId
        );
    }
}
