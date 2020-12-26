<?php

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
