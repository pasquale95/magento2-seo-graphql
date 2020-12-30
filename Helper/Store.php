<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Seo\Hreflang\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Config\Model\Config\Backend\Admin\Custom;

/**
 * Class Store
 * @package Seo\Hreflang\Helper
 */
class Store extends AbstractHelper
{
    const HREFLANG = 'hreflang';
    const BASE_URL = 'baseUrl';

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var FrontendSettings
     */
    protected $frontendSettings;

    /**
     * Store constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param FrontendSettings $frontendSettings
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        FrontendSettings $frontendSettings
    ) {
        $this->storeManager = $storeManager;
        $this->frontendSettings = $frontendSettings;
        parent::__construct($context);
    }

    /**
     * Return the hreflang attribute for a given store.
     *
     * @param string|null $storeId
     * @return string
     */
    public function getHreflang($storeId = null) {
        $code = null;

        $useLocale = $this->scopeConfig->getValue(
            'seo/hreflang/use_locale',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        if ($useLocale) {
            // use locale if option is enabled
            $code = $this->scopeConfig->getValue(
                Custom::XML_PATH_GENERAL_LOCALE_CODE,
                ScopeInterface::SCOPE_STORE,
                $storeId
            );
        } else {
            // otherwise, check for possible custom options
            $code = $this->scopeConfig->getValue(
                'seo/hreflang/custom_hreflang',
                ScopeInterface::SCOPE_STORE,
                $storeId
            );
            if (!$code) {
                //if no custom options, look at the standard hreflang
                $code = $this->scopeConfig->getValue(
                    'seo/hreflang/hreflang_lang',
                    ScopeInterface::SCOPE_STORE,
                    $storeId
                );
            }
        }
        return $code ? str_replace("_", "-", strtolower($code)) : null;
    }

    /**
     * Returns associative array of type ['storeId' => ['hreflang', 'baseUrl']]
     * @return array
     * @throws NoSuchEntityException
     */
    public function getHreflangList()
    {
        $storeList = $this->storeManager->getStores();
        $hreflangs = [];
        foreach ($storeList as $store) {
            $storeId = $store->getId();
            $hreflangs[$storeId] = [
                self::HREFLANG => $this->getHreflang($storeId),
                self::BASE_URL => $this->frontendSettings->getFrontendUrl($storeId)
            ];
        }
        return $hreflangs;
    }
}
