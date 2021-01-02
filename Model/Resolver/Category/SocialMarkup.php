<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\Resolver\Category;

use Magento\CatalogUrlRewrite\Model\CategoryUrlRewriteGenerator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Paskel\Seo\Helper\Url;
use Paskel\Seo\Model\SocialMarkup\AbstractSocialMarkup;

/**
 * Class SocialMarkup:
 * resolve socialMarkup field in category GraphQL query
 *
 * @package Paskel\Seo\Model\Resolver\Category
 */
class SocialMarkup extends AbstractSocialMarkup implements ResolverInterface
{
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
        $this->setSitename(self::SITENAME_VALUE);
        // add url
        $this->setUrl($this->retrieveUrl($category->getId(), CategoryUrlRewriteGenerator::ENTITY_TYPE, $store->getId()));
        // add title
        $this->setTitle($category->getMetaTitle() ?? $category->getName());
        // add description
        $this->setDescription($category->getMetaDescription() ?? $category->getDescription());
        // add image, if any
        $this->setImage($this->retrieveImage($category, $store->getBaseUrl()));

        return $this->socialMarkups;
    }

    /**
     * Retrieve category image url.
     * If not, use placeholder image.
     *
     * @param $category
     * @param $storeUrl
     * @return string
     */
    public function retrieveImage($category, $storeUrl) {
        $imageUrl = $category->getImage();
        if (isset($imageUrl) and !empty($imageUrl)) {
            return Url::pinchUrl($storeUrl . 'catalog/category', $imageUrl);
        } else {
            // return placeholder
            return $this->placeholderProvider->getPlaceholder("small_image");
        }
    }
}
