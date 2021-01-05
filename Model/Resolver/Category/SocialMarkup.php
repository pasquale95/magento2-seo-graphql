<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\Resolver\Category;

use Magento\Catalog\Model\CategoryRepositoryFactory;
use Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\Image\Placeholder as PlaceholderProvider;
use Magento\CatalogUrlRewrite\Model\CategoryUrlRewriteGenerator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
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
 * Class SocialMarkup:
 * @package Paskel\Seo\Model\Resolver\Category
 *
 * Class to resolve socialMarkup field in category GraphQL query.
 */
class SocialMarkup extends AbstractSocialMarkup implements ResolverInterface
{
    /**
     * @var CategoryRepositoryFactory
     */
    protected CategoryRepositoryFactory $categoryRepositoryFactory;

    /**
     * Category resolver constructor.
     *
     * @param CategoryRepositoryFactory $categoryRepositoryFactory
     * @param StoreConfigDataProvider $storeConfigsDataProvider
     * @param HreflangHelper $hreflangHelper
     * @param SocialMarkupHelper $socialMarkupHelper
     * @param PlaceholderProvider $placeholderProvider
     */
    public function __construct(
        CategoryRepositoryFactory $categoryRepositoryFactory,
        StoreConfigDataProvider $storeConfigsDataProvider,
        HreflangHelper $hreflangHelper,
        SocialMarkupHelper $socialMarkupHelper,
        PlaceholderProvider $placeholderProvider
    ) {
        $this->categoryRepositoryFactory = $categoryRepositoryFactory;
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
        // Raise exception if no category model in the request
        if (!isset($value['model'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }
        // retrieve category
        $category = $value['model'];
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
        $this->setUrl($this->retrieveUrl($category->getId(), CategoryUrlRewriteGenerator::ENTITY_TYPE, $store->getId()));
        // add title
        $this->setTitle($category->getMetaTitle() ?? $category->getName());
        // add description
        $this->setDescription($category->getMetaDescription() ?? $category->getDescription());
        // add image, if any
        $this->setImage($this->retrieveImage($category->getId(), $store));

        return $this->socialMarkups;
    }

    /**
     * Retrieve category image url.
     * If not, use placeholder image.
     *
     * @param $categoryId
     * @param $store
     * @return string
     * @throws NoSuchEntityException
     */
    public function retrieveImage($categoryId, $store) {
        // TODO: add twitter cards
        // retrieve store info
        $storeId = $store->getId();
        // factory made necessary to get the image url, if any
        $categoryRepository = $this->categoryRepositoryFactory->create();
        $category = $categoryRepository->get($categoryId, $storeId);

        $imageUrl = $category->getImage();
        if (isset($imageUrl) and !empty($imageUrl)) {
            $imageUrl = UrlHelper::pinchUrl($store->getBaseUrl(), $imageUrl);
        } else {
            $imageUrl = $this->socialMarkupHelper->getImagePlaceholder(
                CategoryUrlRewriteGenerator::ENTITY_TYPE,
                $storeId
            );
            if (isset($imageUrl) and !empty($imageUrl)) {
                // return placeholder
                $imageUrl = UrlHelper::pinchUrl(
                    $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . self::PLACEHOLDER_FOLDER,
                    $imageUrl
                );
            }
        }
        return $imageUrl ?? null;
    }
}
