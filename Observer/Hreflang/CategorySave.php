<?php
/**
 * @author Pasquale Convertini (@Pasquale95)
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Seo\Hreflang\Observer\Hreflang;

use Magento\Catalog\Model\Category;
use Magento\CatalogUrlRewrite\Model\CategoryUrlRewriteGenerator;
use Magento\Cms\Model\Page\Authorization;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Seo\Hreflang\Helper\Hreflang as HreflangHelper;

/**
 * Class CategorySave
 * Trigger the creation/update of hreflang links inside hreflang table for a category entity.
 *
 * @package Seo\Hreflang\Observer\Hreflang
 */
class CategorySave implements ObserverInterface
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
     * HreflangCategorySave constructor.
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
        $category = $observer->getEvent()->getData('category');
        // Check that the loaded obj. is actually a category
        if ($category instanceof Category) {
            $this->hreflangHelper->generateHreflang(
                $category->getId(),
                CategoryUrlRewriteGenerator::ENTITY_TYPE,
                $category->getStoreIds()
            );
        }
    }
}
