<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Seo\Hreflang\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for hreflang search results.
 * @api
 * @since 100.0.2
 */
interface HreflangSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get pages list.
     *
     * @return \Seo\Hreflang\Api\Data\HreflangInterface[]
     */
    public function getItems();

    /**
     * Set pages list.
     *
     * @param \Seo\Hreflang\Api\Data\HreflangInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
