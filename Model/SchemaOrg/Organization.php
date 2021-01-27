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
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Paskel\Seo\Helper\Url as UrlHelper;

/**
 * Class Organization
 * @package Paskel\Seo\Model\SchemaOrg
 */
class Organization extends AbstractSchemaOrg
{
    const SCHEMA_TYPE = "Organization";

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @var Json
     */
    protected Json $jsonHandler;

    /**
     * Organization constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param Json $jsonHandler
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        Json $jsonHandler
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->jsonHandler = $jsonHandler;
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
            'name' => '"' . addslashes($this->getOrganizationName($storeId)) . '"',
            'url' => '"' . addslashes($this->getOrganizationUrl($storeId)) . '"',
            'logo' => '"' . $this->getLogo($store) . '"',
            'sameAs' => $this->getReferenceLinks($storeId)
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
            'seo/schemaOrg/enable_organization',
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
     * Retrieve organization name.
     *
     * @param string|null $storeId
     * @return string
     */
    protected function getOrganizationName($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'seo/schemaOrg/organization_name',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Retrieve organization url.
     *
     * @param string|null $storeId
     * @return string
     */
    protected function getOrganizationUrl($storeId = null) {
        return $this->scopeConfig->getValue(
            'seo/schemaOrg/organization_url',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Retrieve logo.
     *
     * @param $store
     * @return string
     */
    protected function getLogo($store) {
        $logo = $this->scopeConfig->getValue(
            'seo/general/website_logo',
            ScopeInterface::SCOPE_STORE,
            $store->getId()
        );
        if ($logo) {
            // if logo is set, make it an absolute URL
            return UrlHelper::pinchUrl(
                $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) .
                'seo/general/logo',
                $logo
            );
        }
        return null;
    }

    /**
     * Retrieve relative links
     *
     * @param $storeId
     * @return string
     */
    protected function getReferenceLinks($storeId) {
        $relativeLinks = $this->scopeConfig->getValue(
            'seo/schemaOrg/reference_links',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        if ($relativeLinks) {
            $links = $this->jsonHandler->unserialize($relativeLinks);
            if ($links) {
                // if at least a link is set, return it
                $linksString = '[';
                foreach ($links as $link) {
                    $linksString .= '"' . addslashes($link['link']) . '",';
                }
                return rtrim($linksString, ',') . ']';
            }
        }
        return null;
    }
}