<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\Resolver\Product;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Paskel\Seo\Model\SchemaOrg\Organization;
use Paskel\Seo\Model\SchemaOrg\Product;
use Paskel\Seo\Model\SchemaOrg\Website;

/**
 * Class SchemaOrg
 * @package Paskel\Seo\Model\Resolver\Product
 */
class SchemaOrg implements ResolverInterface
{
    /**
     * @var Organization
     */
    protected Organization $organizationSchema;

    /**
     * @var Website
     */
    protected Website $websiteSchema;

    /**
     * @var Product
     */
    protected Product $productSchema;

    /**
     * SchemaOrg constructor.
     *
     * @param Organization $organizationSchema
     * @param Website $websiteSchema
     * @param Product $productSchema
     */
    public function __construct(
        Organization $organizationSchema,
        Website $websiteSchema,
        Product $productSchema
    ) {
        $this->organizationSchema = $organizationSchema;
        $this->websiteSchema = $websiteSchema;
        $this->productSchema = $productSchema;
    }

    /**
     * @inheritDoc
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
        // retrieve storeId
        $storeId = $context->getExtensionAttributes()->getStore()->getId();

        // return enabled schemas
        $schemas = [];
        if ($this->organizationSchema->isEnabled($storeId)) {
            $schemas[] = [
                'schemaType' => $this->organizationSchema->getType(),
                'script' => $this->organizationSchema->getScript()
            ];
        }
        if ($this->websiteSchema->isEnabled($storeId)) {
            $schemas[] = [
                'schemaType' => $this->websiteSchema->getType(),
                'script' => $this->websiteSchema->getScript()
            ];
        }
        if ($this->productSchema->isEnabled($storeId)) {
            $schemas[] = [
                'schemaType' => $this->productSchema->getType(),
                'script' => $this->productSchema->getScript($product->getId())
            ];
        }
        return $schemas;
    }
}
