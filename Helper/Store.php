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
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Config\Model\Config\Backend\Admin\Custom;
use Paskel\Seo\Api\Data\HreflangInterface;

/**
 * Class Store
 * @package Paskel\Seo\Helper
 */
class Store extends AbstractHelper
{
    /**
     * Constants used as keys inside the hreflang list.
     */
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
     * @var Json
     */
    protected Json $jsonHandler;

    /**
     * Store constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param FrontendSettings $frontendSettings
     * @param Json $jsonHandler
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        FrontendSettings $frontendSettings,
        Json $jsonHandler
    ) {
        $this->storeManager = $storeManager;
        $this->frontendSettings = $frontendSettings;
        $this->jsonHandler = $jsonHandler;
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

        $xDefaultStoreId = $this->scopeConfig->getValue(
            'seo/hreflang/x_default_hreflang'
        );

        // return x-default as hreflang for x-default store
        if ($storeId == $xDefaultStoreId) {
            return HreflangInterface::X_DEFAULT;
        }

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
            $customCode = $this->scopeConfig->getValue(
                'seo/hreflang/custom_hreflang',
                ScopeInterface::SCOPE_STORE,
                $storeId
            );
            if ($customCode) {
                $config = current($this->jsonHandler->unserialize($customCode));
                if (is_array($config) and array_key_exists('language', $config)
                    and array_key_exists('country', $config)
                ) {
                    if ($config['country']) {
                        // country specified -> create hreflang code of type <language-country>
                        $code = $config['language'] . "-" . $config['country'];
                    } else {
                        // no country specified -> create hreflang code of type <language>
                        $code = $config['language'];
                    }
                }
            }

            // fallback logic: return hreflang code if no custom code has been defined
            if (!isset($code)) {
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
     *
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
