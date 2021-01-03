<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface HreflangSearchResultsInterface
 * @package Paskel\Seo\Api\Data
 */
interface HreflangSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get pages list.
     *
     * @return \Paskel\Seo\Api\Data\HreflangInterface[]
     */
    public function getItems();

    /**
     * Set pages list.
     *
     * @param \Paskel\Seo\Api\Data\HreflangInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
