<?php

namespace Seo\Hreflang\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class FrontendSettings extends AbstractHelper
{
    /**
     * Return the frontend URL specified at backoffice
     * (Stores -> Configuration -> General -> Web -> Frontend Settings -> Frontend URL)
     *
     * @param string|null $storeId
     * @return string
     */
    public function getFrontendUrl($storeId = null)
    {
        return $this->scopeConfig->getValue('web/frontend_settings/frontend_url', ScopeInterface::SCOPE_STORE, $storeId);
    }
}
