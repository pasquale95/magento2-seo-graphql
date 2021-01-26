<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\SocialMarkup\Category;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\StoreGraphQl\Model\Resolver\Store\StoreConfigDataProvider;
use Magento\Catalog\Model\Category;
use Paskel\Seo\Helper\SocialMarkup as SocialMarkupHelper;
use Paskel\Seo\Helper\Hreflang as HreflangHelper;
use Paskel\Seo\Api\Data\TwitterCardInterface;

/**
 * Class TwitterCard
 * @package Paskel\Seo\Model\SocialMarkup\Category
 */
class TwitterCard extends AbstractSocialMarkup implements TwitterCardInterface
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
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * AbstractSocialMarkup constructor.
     *
     * @param SocialMarkupHelper $socialMarkupHelper
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreConfigDataProvider $storeConfigsDataProvider
     * @param HreflangHelper $hreflangHelper
     */
    public function __construct(
        SocialMarkupHelper $socialMarkupHelper,
        ScopeConfigInterface $scopeConfig,
        StoreConfigDataProvider $storeConfigsDataProvider,
        HreflangHelper $hreflangHelper
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeConfigDataProvider = $storeConfigsDataProvider;
        $this->hreflangHelper = $hreflangHelper;
        parent::__construct($socialMarkupHelper);
    }

    /**
     * Retrieve OpenGraph tags
     *
     * @param Category $item
     * @param $store
     * @return array
     */
    public function getTags($item, $store) {
        $storeId = $store->getId();
        // add tags
        $tags = [
            self::CARD => $this->getCard($storeId),
            self::SITE => $this->getSite($storeId),
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
     * Return the twitter card type.
     *
     * @param $storeId
     * @return string
     */
    protected function getCard($storeId) {
        return $this->socialMarkupHelper->getTwitterCardType($storeId);
    }

    /**
     * Return the twitter card site.
     *
     * @param $storeId
     * @return string
     */
    protected function getSite($storeId) {
        return $this->socialMarkupHelper->getTwitterHandle($storeId);
    }
}
