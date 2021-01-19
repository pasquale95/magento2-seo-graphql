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
use Paskel\Seo\Model\SocialMarkup\CmsPage\OpenGraph;

/**
 * Class SocialMarkup
 * @package Paskel\Seo\Model\Resolver\CmsPage
 *
 * Class to resolve socialMarkup field in cmsPage GraphQL query.
 */
class SocialMarkup implements ResolverInterface
{
    /**
     * @var OpenGraph
     */
    protected OpenGraph $openGraph;

    /**
     * @var PageRepositoryInterface
     */
    protected PageRepositoryInterface $pageRepository;

    public function __construct(
        PageRepositoryInterface $pageRepository,
        OpenGraph $openGraph
    ) {
        $this->pageRepository = $pageRepository;
        $this->openGraph = $openGraph;
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
        // retrieve page
        $page = $this->pageRepository->getById($value[PageInterface::PAGE_ID]);
        // retrieve store
        $store = $context->getExtensionAttributes()->getStore();

        return [
            'openGraph' => $this->openGraph->getTags($page, $store),
            'twitterCard' => null
        ];
    }
}
