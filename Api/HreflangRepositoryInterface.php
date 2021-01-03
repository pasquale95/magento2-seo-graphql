<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Api;

/**
 * Interface HreflangRepositoryInterface
 * @package Paskel\Seo\Api
 *
 *                              ---CAUTION---
 * Be careful to do not remove the full path for the params and return types.
 * The use statements are not parsed by the Magento framework to generate JSON
 * and XML responses, thus is a good practice to use the entire path in the
 * methods documentation inside API interfaces.
 */
interface HreflangRepositoryInterface
{
    /**
     * @param int $id
     * @return \Paskel\Seo\Api\Data\HreflangInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id);

    /**
     * @param \Paskel\Seo\Api\Data\HreflangInterface $hreflang
     * @return \Paskel\Seo\Api\Data\HreflangInterface
     */
    public function save(\Paskel\Seo\Api\Data\HreflangInterface $hreflang);

    /**
     * @param \Paskel\Seo\Api\Data\HreflangInterface $hreflang
     * @return void
     */
    public function delete(\Paskel\Seo\Api\Data\HreflangInterface $hreflang);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Paskel\Seo\Api\Data\HreflangSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
