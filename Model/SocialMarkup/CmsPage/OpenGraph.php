<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\SocialMarkup\CmsPage;

use Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\Image\Placeholder as PlaceholderProvider;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\CmsUrlRewrite\Model\CmsPageUrlRewriteGenerator;
use Magento\Framework\UrlInterface;
use Magento\StoreGraphQl\Model\Resolver\Store\StoreConfigDataProvider;
use Paskel\Seo\Helper\Hreflang as HreflangHelper;
use Paskel\Seo\Helper\SocialMarkup as SocialMarkupHelper;
use Paskel\Seo\Helper\Url as UrlHelper;
use Paskel\Seo\Model\SocialMarkup\AbstractOpenGraph;

/**
 * Class OpenGraph
 * @package Paskel\Seo\Model\SocialMarkup\CmsPage
 */
class OpenGraph extends AbstractOpenGraph
{
    /**
     * Retrieve OpenGraph tags
     *
     * @param $page
     * @param $store
     * @return array
     */
    public function getTags($page, $store) {
        //$page = $this->pageRepository->getById($value[PageInterface::PAGE_ID]);
        // retrieve store
        //$store = $context->getExtensionAttributes()->getStore();
        // initialise socialMarkups
        //$this->socialMarkups = [];

        // add type
        $this->setType(self::TYPE_VALUE);
        // add locale
        $this->setLocale($this->storeConfigDataProvider->getStoreConfigData($store)['locale']);
        // add site_name
        $this->setSitenameByStore($store->getId());
        // add url
        $this->setUrl($this->socialMarkupHelper->retrieveUrl(
            $page->getIdentifier(),
            CmsPageUrlRewriteGenerator::ENTITY_TYPE,
            $store->getId()
        ));
        // add title
        $this->setTitle(!empty($value['meta_title']) ?
            $value['meta_title'] : $page->getTitle());
        // add description
        $this->setDescription(!empty($value['meta_description']) ?
            $value['meta_description'] : $page->getContentHeading());
        // add image, if any
        $this->setImage($this->retrieveImage($page, $store));

        return $this->tags;
    }

    /**
     * Retrieve cms page image url.
     * If not, use placeholder image.
     *
     * @param $page
     * @param $store
     * @return string|null
     */
    public function retrieveImage($page, $store) {
        // retrieve store info
        $storeId = $store->getId();
        $storeUrl = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

        $imageUrl = $page->getSocialMarkupImage();
        if (!$imageUrl) {
            $imageUrl = $this->socialMarkupHelper->getImagePlaceholder(
                CmsPageUrlRewriteGenerator::ENTITY_TYPE,
                $storeId
            );
            if (!empty($imageUrl)) {
                // return placeholder
                $imageUrl = UrlHelper::pinchUrl($storeUrl . self::PLACEHOLDER_FOLDER, $imageUrl);
            }
        }
        return $imageUrl ?? null;
    }
}
