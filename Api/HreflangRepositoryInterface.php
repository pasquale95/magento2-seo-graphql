<?php

namespace Seo\Hreflang\Api;

/**
 * Interface HreflangRepositoryInterface
 *
 *                              !!!CAUTION!!!
 * Be careful to do not remove the full path for the params and return types.
 * The use statements are not parsed by the Magento framework to generate JSON
 * and XML responses, thus is a good practice to use the entire path in the
 * methods documentation inside API interfaces.
 *
 * @package Seo\Hreflang\Api
 */
interface HreflangRepositoryInterface
{
    /**
     * @param int $id
     * @return \Seo\Hreflang\Api\Data\HreflangInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id);

    /**
     * @param \Seo\Hreflang\Api\Data\HreflangInterface $hreflang
     * @return \Seo\Hreflang\Api\Data\HreflangInterface
     */
    public function save(\Seo\Hreflang\Api\Data\HreflangInterface $hreflang);

    /**
     * @param \Seo\Hreflang\Api\Data\HreflangInterface $hreflang
     * @return void
     */
    public function delete(\Seo\Hreflang\Api\Data\HreflangInterface $hreflang);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Seo\Hreflang\Api\Data\HreflangSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
