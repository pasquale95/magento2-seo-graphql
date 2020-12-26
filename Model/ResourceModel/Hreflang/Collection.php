<?php

namespace Seo\Hreflang\Model\ResourceModel\Hreflang;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Seo\Hreflang\Api\Data\HreflangInterface;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = HreflangInterface::HREFLANG_ID;

    /**
     * @var string
     */
    protected $_eventPrefix = 'hreflang_collection';

    /**
     * @var string
     */
    protected $_eventObject = 'hreflang_collection';

    /**
     * Define collection resource model by connecting model and resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Seo\Hreflang\Model\Hreflang', 'Seo\Hreflang\Model\ResourceModel\Hreflang');
    }
}
