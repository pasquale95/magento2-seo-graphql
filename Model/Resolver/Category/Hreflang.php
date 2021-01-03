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
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Paskel\Seo\Helper\Hreflang as HreflangHelper;

/**
 * Class Hreflang
 * @package Paskel\Seo\Model\Resolver\Category
 *
 * Class to resolve hreflang field in CategoryInterface GraphQL query.
 */
class Hreflang implements ResolverInterface
{
    /**
     * @var HreflangHelper
     */
    protected $hreflangHelper;

    /**
     * @param HreflangHelper $hreflangHelper
     */
    public function __construct(
        HreflangHelper $hreflangHelper
    ) {
        $this->hreflangHelper = $hreflangHelper;
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
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        // retrieve hreflangs
        $storeId = $context->getExtensionAttributes()->getStore()->getStoreId();
        $hreflang = $this->hreflangHelper->getStoresHrefLang(
            $value['id'],
            CategoryUrlRewriteGenerator::ENTITY_TYPE,
            $storeId
        );

        // if entries are available, insert them in the array
        $hreflangList = [];
        foreach ($hreflang as $code => $url) {
            array_push(
                $hreflangList,
                [
                    'code' => $code,
                    'href' => $url
                ]
            );
        }

        return empty($hreflangList) ? null : $hreflangList;
    }
}
