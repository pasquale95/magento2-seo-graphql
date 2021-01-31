<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\SchemaOrg;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Paskel\Seo\Helper\FrontendSettings;

/**
 * Class Website
 * @package Paskel\Seo\Model\SchemaOrg
 */
class Website extends AbstractSchemaOrg
{
    const SCHEMA_TYPE = "WebSite";

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @var FrontendSettings
     */
    protected FrontendSettings $frontendSettingsHelper;

    /**
     * Website constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param FrontendSettings $frontendSettingsHelper
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        FrontendSettings $frontendSettingsHelper
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->frontendSettingsHelper = $frontendSettingsHelper;
    }

    /**
     * @inheritDoc
     */
    public function getType() {
        return self::SCHEMA_TYPE;
    }

    /**
     * @inheritDoc
     * @throws NoSuchEntityException
     */
    public function getProperties() {
        $store = $this->storeManager->getStore();
        $storeId = $store->getId();
        return [
            '@context' => '"' . $this->getSchemaContext() . '"',
            '@type' => '"' . $this->getSchemaType() . '"',
            'name' => '"' . addslashes($this->getWebsiteName($storeId)) . '"',
            'url' => '"' . addslashes($this->getWebsiteUrl()) . '"'
        ];
    }

    /**
     * Return if the schema.org has been enabled in the config.
     *
     * @param null $storeId
     * @return mixed
     */
    public function isEnabled($storeId = null) {
        return $this->scopeConfig->getValue(
            'seo/schemaOrg/enable_website',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Returns the schema context
     *
     * @return string
     */
    protected function getSchemaContext() {
        return self::SCHEMA_CONTEXT;
    }

    /**
     * Returns the schema type
     *
     * @return string
     */
    protected function getSchemaType() {
        return self::SCHEMA_TYPE;
    }

    /**
     * Retrieve website name.
     *
     * @param string|null $storeId
     * @return string
     */
    protected function getWebsiteName($storeId = null) {
        return $this->scopeConfig->getValue(
            'seo/general/site_name',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Retrieve website url.
     *
     * @return string
     * @throws NoSuchEntityException
     */
    protected function getWebsiteUrl() {
        return $this->frontendSettingsHelper->getFrontendUrl(Store::DEFAULT_STORE_ID);
    }
}