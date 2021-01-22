<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class FrontendSettings
 * @package Paskel\Seo\Helper
 */
class FrontendSettings extends AbstractHelper
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * FrontendSettings constructor
     *
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * If enabled, return the PWA frontend URL specified at backoffice
     * (Stores -> Configuration -> General -> Web -> Frontend Settings -> Frontend URL).
     *
     * @param string|null $storeId
     * @return string
     * @throws NoSuchEntityException
     */
    public function getFrontendUrl($storeId = null)
    {
        $pwaFrontend = $this->scopeConfig->getValue(
            'seo/general/use_pwa_frontend',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        if ($pwaFrontend) {
            return $this->scopeConfig->getValue(
                'seo/general/frontend_url',
                ScopeInterface::SCOPE_STORE,
                $storeId
            );
        } else {
            return $this->storeManager->getStore()->getBaseUrl();
        }
    }
}