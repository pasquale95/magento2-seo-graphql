<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\Resolver\CmsPage;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Paskel\Seo\Model\SchemaOrg\Organization;
use Paskel\Seo\Model\SchemaOrg\Website;

/**
 * Class SchemaOrg
 * @package Paskel\Seo\Model\Resolver\CmsPage
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
     * SchemaOrg constructor.
     *
     * @param Organization $organizationSchema
     * @param Website $websiteSchema
     */
    public function __construct(
        Organization $organizationSchema,
        Website $websiteSchema
    ) {
        $this->organizationSchema = $organizationSchema;
        $this->websiteSchema = $websiteSchema;
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
        return $schemas;
    }
}
