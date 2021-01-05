<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Store
 * @package Paskel\Seo\Model\Config\Source
 */
class Store implements ArrayInterface
{
    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * Store constructor.
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $storeManagerDataList = $this->storeManager->getStores();
        $options = [];
        foreach ($storeManagerDataList as $key => $value) {
            $options[] = [
                'label' => $value['name'],
                'value' => $key
            ];
        }
        return $options;
    }
}
