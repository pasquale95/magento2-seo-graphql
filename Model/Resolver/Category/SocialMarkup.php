<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\Resolver\Category;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Paskel\Seo\Helper\SocialMarkup as SocialMarkupHelper;
use Paskel\Seo\Model\SocialMarkup\Category\OpenGraph;
use Paskel\Seo\Model\SocialMarkup\Category\TwitterCard;

/**
 * Class SocialMarkup
 * @package Paskel\Seo\Model\Resolver\Category
 */
class SocialMarkup implements ResolverInterface
{
    /**
     * @var OpenGraph
     */
    protected OpenGraph $openGraph;

    /**
     * @var CategoryRepositoryInterface
     */
    protected CategoryRepositoryInterface $categoryRepository;

    /**
     * @var TwitterCard
     */
    protected TwitterCard $twitterCard;

    /**
     * @var SocialMarkupHelper
     */
    protected SocialMarkupHelper $socialMarkupHelper;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        OpenGraph $openGraph,
        TwitterCard $twitterCard,
        SocialMarkupHelper $socialMarkupHelper
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->openGraph = $openGraph;
        $this->twitterCard = $twitterCard;
        $this->socialMarkupHelper = $socialMarkupHelper;
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
     * @throws NoSuchEntityException
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
        $socialMarkups = [];
        // retrieve store
        $store = $context->getExtensionAttributes()->getStore();
        // retrieve category
        /** @var Category $category */
        $category = $this->categoryRepository->get($value['model']->getId(), $store->getId());
        // retrieve openGraph tags and add them to the array
        $socialMarkups['openGraph'] = $this->socialMarkupHelper->formatOpenGraphTagsForGraphQl(
            $this->openGraph->getTags($category, $store)
        );
        // if twitter cards are enabled, add twitter tags
        if ($this->socialMarkupHelper->isTwitterCardEnabled($store->getId())) {
            $socialMarkups['twitterCard'] = $this->socialMarkupHelper->formatTwitterCardTagsForGraphQl(
                $this->twitterCard->getTags($category, $store)
            );
        }

        return $socialMarkups;
    }
}
