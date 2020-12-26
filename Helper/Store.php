<?php

namespace Seo\Hreflang\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

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
     * Return the hreflang attribute for a given store
     *
     * @param string|null $storeId
     * @return string
     */
    public function getHreflang($storeId = null) {
        return $this->scopeConfig->getValue('web/seo/hreflang', ScopeInterface::SCOPE_STORE, $storeId);
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
