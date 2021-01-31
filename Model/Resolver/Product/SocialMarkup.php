<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\Resolver\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Paskel\Seo\Helper\SocialMarkup as SocialMarkupHelper;
use Paskel\Seo\Model\SocialMarkup\Product\OpenGraph;
use Paskel\Seo\Model\SocialMarkup\Product\TwitterCard;

/**
 * Class SocialMarkup
 * @package Paskel\Seo\Model\Resolver\Product
 */
class SocialMarkup implements ResolverInterface
{
    /**
     * @var OpenGraph
     */
    protected OpenGraph $openGraph;

    /**
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * @var TwitterCard
     */
    protected TwitterCard $twitterCard;

    /**
     * @var SocialMarkupHelper
     */
    protected SocialMarkupHelper $socialMarkupHelper;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        OpenGraph $openGraph,
        TwitterCard $twitterCard,
        SocialMarkupHelper $socialMarkupHelper
    ) {
        $this->productRepository = $productRepository;
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
        $socialMarkups = [];
        // Raise exception if no product model in the request
        if (!isset($value['model'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }
        // retrieve store
        $store = $context->getExtensionAttributes()->getStore();
        // retrieve product
        /** @var Product $product */
        $product = $this->productRepository->getById($value['model']->getId());
        // retrieve openGraph tags and add them to the array
        $socialMarkups['openGraph'] = $this->socialMarkupHelper->formatOpenGraphTagsForGraphQl(
            $this->openGraph->getTags($product, $store)
        );
        // if twitter cards are enabled, add twitter tags
        if ($this->socialMarkupHelper->isTwitterCardEnabled($store->getId())) {
            $socialMarkups['twitterCard'] = $this->socialMarkupHelper->formatTwitterCardTagsForGraphQl(
                $this->twitterCard->getTags($product, $store)
            );
        }

        return $socialMarkups;
    }
}
