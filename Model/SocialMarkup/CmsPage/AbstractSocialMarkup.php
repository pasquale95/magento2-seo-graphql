<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\SocialMarkup\CmsPage;

use Magento\Cms\Model\Page;
use Magento\CmsUrlRewrite\Model\CmsPageUrlRewriteGenerator;
use Magento\Framework\UrlInterface;
use Paskel\Seo\Api\Data\SocialMarkupInterface;
use Paskel\Seo\Helper\SocialMarkup as SocialMarkupHelper;
use Paskel\Seo\Helper\Url as UrlHelper;

/**
 * Class AbstractSocialMarkup
 * @package Paskel\Seo\Model\SocialMarkup\CmsPage
 */
abstract class AbstractSocialMarkup implements SocialMarkupInterface
{
    /**
     * @var array
     */
    protected $tags = [];

    /**
     * @var SocialMarkupHelper
     */
    protected SocialMarkupHelper $socialMarkupHelper;

    /**
     * AbstractSocialMarkup constructor.
     *
     * @param SocialMarkupHelper $socialMarkupHelper
     */
    public function __construct(
        SocialMarkupHelper $socialMarkupHelper
    ) {
        $this->socialMarkupHelper = $socialMarkupHelper;
    }

    /**
     * Returns the page title.
     *
     * @param Page $page
     * @return string
     */
    protected function getTitle($page) {
        return $page->getTitle();
    }

    /**
     * Returns the page description.
     *
     * @param Page $page
     * @return string
     */
    protected function getDescription($page) {
        return $page->getMetaDescription() ?? $page->getContentHeading();
    }

    /**
     * Returns the page image.
     * If not set, use placeholder image.
     *
     * @param Page $page
     * @param $store
     * @return string
     */
    protected function getImage($page, $store) {
        // retrieve store info
        $storeId = $store->getId();

        $imageUrl = $page->getData('social_markup_image');
        if (!$imageUrl) {
            $imageUrl = $this->socialMarkupHelper->getImagePlaceholder(
                CmsPageUrlRewriteGenerator::ENTITY_TYPE,
                $storeId
            );
            if (!empty($imageUrl)) {
                // return placeholder
                $storeMediaUrl = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
                $imageUrl = UrlHelper::pinchUrl($storeMediaUrl . self::PLACEHOLDER_FOLDER, $imageUrl);
            }
        }
        return $imageUrl ?? null;
    }
}
