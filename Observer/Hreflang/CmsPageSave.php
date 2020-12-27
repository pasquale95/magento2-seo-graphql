<?php
/**
 * @author Pasquale Convertini (@Pasquale95)
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Seo\Hreflang\Observer\Hreflang;

use Magento\Cms\Model\Page;
use Magento\CmsUrlRewrite\Model\CmsPageUrlRewriteGenerator;
use Magento\Cms\Model\Page\Authorization;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Seo\Hreflang\Helper\Hreflang as HreflangHelper;

/**
 * Class CmsPageSave
 * Trigger the creation/update of hreflang links inside hreflang table for a page entity.
 *
 * @package Seo\Hreflang\Observer\Hreflang
 */
class CmsPageSave implements ObserverInterface
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
     * HreflangPageSave constructor.
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
        $page = $observer->getEvent()->getData('page');
        // Check that the loaded obj. is actually a page
        if ($page instanceof Page) {
            $this->hreflangHelper->generateHreflang(
                $page->getId(),
                CmsPageUrlRewriteGenerator::ENTITY_TYPE,
                $page->getStores(),
                $page->getIdentifier()
            );
        }
    }
}
