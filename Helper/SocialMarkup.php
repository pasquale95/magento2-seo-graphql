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
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\ScopeInterface;

/**
 * Class SocialMarkup
 * @package Paskel\Seo\Helper
 */
class SocialMarkup extends AbstractHelper
{
    /**
     * @var Hreflang
     */
    protected Hreflang $hreflangHelper;

    /**
     * SocialMarkup constructor.
     *
     * @param Context $context
     * @param Hreflang $hreflangHelper
     */
    public function __construct(
        Context $context,
        Hreflang $hreflangHelper
    ) {
        $this->hreflangHelper = $hreflangHelper;
        parent::__construct($context);
    }

    /**
     * Retrieve site name.
     *
     * @param null $storeId
     * @return mixed
     */
    public function getSitename($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'seo/general/site_name',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Retrieve unset properties option.
     *
     * @return mixed
     */
    public function hideUnsetPropertiesInGraphQl()
    {
        return $this->scopeConfig->getValue(
            'seo/socialMarkup/hide_unset_properties_graphql'
        );
    }

    /**
     * Retrieve entity image placeholder.
     *
     * @param $entityType
     * @param int|null $storeId
     * @return mixed
     */
    public function getImagePlaceholder($entityType, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            'seo/socialMarkup/' . str_replace("-", "_", $entityType) . '_placeholder',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Retrieve full url using hreflang table.
     *
     * @param $entityId
     * @param $entityType
     * @param $storeId
     * @return string|null
     */
    public function retrieveUrl($entityId, $entityType, $storeId) {
        try {
            $hreflang = $this->hreflangHelper->getStoreHreflang(
                $entityId,
                $entityType,
                $storeId
            );
            return $hreflang->getUrl();
        }
        catch (LocalizedException $e) {
            return null;
        }
    }
}