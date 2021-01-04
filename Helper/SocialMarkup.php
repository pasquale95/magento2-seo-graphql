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
use Magento\Store\Model\ScopeInterface;

/**
 * Class SocialMarkup
 * @package Paskel\Seo\Helper
 */
class SocialMarkup extends AbstractHelper
{
    /**
     * Retrieve site name.
     *
     * @param null $storeId
     * @return mixed
     */
    public function getSitename($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'seo/socialMarkup/site_name',
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
}