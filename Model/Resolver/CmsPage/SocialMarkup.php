<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\Resolver\CmsPage;

use Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\Image\Placeholder as PlaceholderProvider;
use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\CmsUrlRewrite\Model\CmsPageUrlRewriteGenerator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\UrlInterface;
use Magento\StoreGraphQl\Model\Resolver\Store\StoreConfigDataProvider;
use Paskel\Seo\Helper\Hreflang as HreflangHelper;
use Paskel\Seo\Helper\SocialMarkup as SocialMarkupHelper;
use Paskel\Seo\Helper\Url as UrlHelper;
use Paskel\Seo\Model\SocialMarkup\AbstractSocialMarkup;

/**
 * Class SocialMarkup
 * @package Paskel\Seo\Model\Resolver\CmsPage
 *
 * Class to resolve socialMarkup field in cmsPage GraphQL query.
 */
class SocialMarkup extends AbstractSocialMarkup implements ResolverInterface
{
    /**
     * @var PageRepositoryInterface
     */
    protected $pageRepository;

    /**
     * CmsPage resolver constructor.
     *
     * @param PageRepositoryInterface $pageRepository
     * @param StoreConfigDataProvider $storeConfigsDataProvider
     * @param HreflangHelper $hreflangHelper
     * @param SocialMarkupHelper $socialMarkupHelper
     * @param PlaceholderProvider $placeholderProvider
     */
    public function __construct(
        PageRepositoryInterface $pageRepository,
        StoreConfigDataProvider $storeConfigsDataProvider,
        HreflangHelper $hreflangHelper,
        SocialMarkupHelper $socialMarkupHelper,
        PlaceholderProvider $placeholderProvider
    ) {
        $this->pageRepository = $pageRepository;
        parent::__construct(
            $storeConfigsDataProvider,
            $hreflangHelper,
            $placeholderProvider,
            $socialMarkupHelper
        );
    }

    /**
     * Fetches the data from persistence models and format it according to the GraphQL schema.
     *
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array|Value
     * @throws LocalizedException
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $page = $this->pageRepository->getById($value[PageInterface::PAGE_ID]);
        // retrieve store
        $store = $context->getExtensionAttributes()->getStore();
        // initialise socialMarkups
        $this->socialMarkups = [];

        // add type
        $this->setType(self::TYPE_VALUE);
        // add locale
        $this->setLocale($this->storeConfigDataProvider->getStoreConfigData($store)['locale']);
        // add site_name
        $this->setSitenameByStore($store->getId());
        // add url
        $this->setUrl($this->retrieveUrl($value[PageInterface::IDENTIFIER], CmsPageUrlRewriteGenerator::ENTITY_TYPE, $store->getId()));
        // add title
        // TODO: fix why if not meta title is set, it returns nothing
        $this->setTitle($value['meta_title'] ?? $page->getTitle());
        // add description
        $this->setDescription($value['meta_description'] ?? $page->getContentHeading());
        // add image, if any
        $this->setImage($this->retrieveImage($page, $store));

        return $this->socialMarkups;
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
            if (isset($imageUrl) and !empty($imageUrl)) {
                // return placeholder
                $imageUrl = UrlHelper::pinchUrl($storeUrl . self::PLACEHOLDER_FOLDER, $imageUrl);
            }
        }
        return $imageUrl ?? null;
    }
}
