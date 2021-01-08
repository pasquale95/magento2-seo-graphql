<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\Resolver\Product;

use Magento\Catalog\Model\ProductRepositoryFactory;
use Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\Image\Placeholder as PlaceholderProvider;
use Magento\CatalogUrlRewrite\Model\ProductUrlRewriteGenerator;
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
 * Class SocialMarkup
 * @package Paskel\Seo\Model\Resolver\Product
 *
 * Class to resolve socialMarkup field in product GraphQL query.
 */
class SocialMarkup extends AbstractSocialMarkup implements ResolverInterface
{
    /**
     * @var ProductRepositoryFactory
     */
    protected ProductRepositoryFactory $productRepositoryFactory;

    /**
     * Product resolver constructor.
     *
     * @param ProductRepositoryFactory $productRepositoryFactory
     * @param StoreConfigDataProvider $storeConfigsDataProvider
     * @param HreflangHelper $hreflangHelper
     * @param SocialMarkupHelper $socialMarkupHelper
     * @param PlaceholderProvider $placeholderProvider
     */
    public function __construct(
        ProductRepositoryFactory $productRepositoryFactory,
        StoreConfigDataProvider $storeConfigsDataProvider,
        HreflangHelper $hreflangHelper,
        SocialMarkupHelper $socialMarkupHelper,
        PlaceholderProvider $placeholderProvider
    ) {
        $this->productRepositoryFactory = $productRepositoryFactory;
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
        // Raise exception if no product model in the request
        if (!isset($value['model'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }
        // retrieve product
        $product = $value['model'];
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
        $this->setUrl($this->retrieveUrl($product->getId(), ProductUrlRewriteGenerator::ENTITY_TYPE, $store->getId()));
        // add title
        $this->setTitle(!empty($product->getMetaTitle()) ?
            $product->getMetaTitle() : $product->getName());
        // add description
        $this->setDescription(!empty($product->getMetaDescription()) ?
            $product->getMetaDescription() : $product->getDescription());
        // add image, if any
        $this->setImage($this->retrieveImage($product->getId(), $store));

        return $this->socialMarkups;
    }

    /**
     * Retrieve product image url.
     * If not, use placeholder image.
     *
     * @param $productId
     * @param $store
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function retrieveImage($productId, $store) {
        // TODO: add twitter cards
        // retrieve store info
        $storeId = $store->getId();
        $storeUrl = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        // factory made necessary to get the image, if any
        $productFactory = $this->productRepositoryFactory->create();
        $product = $productFactory->getById($productId);

        $imageUrl = $product->getImage();
        if (!empty($imageUrl)) {
            $imageUrl = UrlHelper::pinchUrl($storeUrl . 'catalog/product', $imageUrl);
        } else {
            $imageUrl = $this->socialMarkupHelper->getImagePlaceholder(
                ProductUrlRewriteGenerator::ENTITY_TYPE,
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
