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
     * Retrieve option value
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
     * Retrieve option value
     *
     * @return mixed
     */
    public function hideUnsetPropertiesInGraphQl()
    {
        return $this->scopeConfig->getValue(
            'seo/socialMarkup/hide_unset_properties_graphql'
        );
    }
}