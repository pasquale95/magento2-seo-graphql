<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Observer\Hreflang;

use Magento\Catalog\Model\Product;
use Magento\CatalogUrlRewrite\Model\ProductUrlRewriteGenerator;
use Magento\Cms\Model\Page\Authorization;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Paskel\Seo\Helper\Hreflang as HreflangHelper;

/**
 * Class ProductSave
 * @package Paskel\Seo\Observer\Hreflang
 *
 * Trigger the creation/update of hreflang links inside hreflang table for a product entity.
 */
class ProductSave implements ObserverInterface
{
    /**
     * @var Authorization
     */
    private $authorization;

    /**
     * @var HreflangHelper
     */
    protected $hreflangHelper;

    /**
     * HreflangProductSave constructor.
     * @param Authorization $authorization
     * @param HreflangHelper $hreflangHelper
     */
    public function __construct(
        Authorization $authorization,
        HreflangHelper $hreflangHelper
    ) {
        $this->authorization = $authorization;
        $this->hreflangHelper = $hreflangHelper;
    }

    /**
     * @inheritDoc
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        $product = $observer->getEvent()->getData('product');
        // Check that the loaded obj. is actually a product
        if ($product instanceof Product) {
            $categoryIds = $product->getCategoryIds();
            $this->hreflangHelper->generateHreflang(
                $product->getId(),
                ProductUrlRewriteGenerator::ENTITY_TYPE,
                $product->getStoreIds(),
                null,
                $categoryIds ? max($categoryIds) : null
            );
        }
    }
}
