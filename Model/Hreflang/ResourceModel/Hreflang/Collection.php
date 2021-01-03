<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\Hreflang\ResourceModel\Hreflang;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Paskel\Seo\Api\Data\HreflangInterface;

/**
 * Class Collection
 * @package Paskel\Seo\Model\Hreflang\ResourceModel\Hreflang
 */
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
        $this->_init('Paskel\Seo\Model\Hreflang\Hreflang', 'Paskel\Seo\Model\Hreflang\ResourceModel\Hreflang');
    }
}
