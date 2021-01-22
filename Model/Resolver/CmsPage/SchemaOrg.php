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
use Paskel\Seo\Model\SchemaOrg\Organization;
use Paskel\Seo\Model\SchemaOrg\Website;
use Paskel\Seo\Model\SocialMarkup\CmsPage\OpenGraph;

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
        return [
            [
                'schemaType' => $this->organizationSchema->getType(),
                'script' => $this->organizationSchema->getScript()
            ],
            [
                'schemaType' => $this->websiteSchema->getType(),
                'script' => $this->websiteSchema->getScript()
            ]
        ];
    }
}
