<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Api\Data;

/**
 * Interface HreflangInterface
 * @package Paskel\Seo\Api\Data
 */
interface HreflangInterface
{
    /**
     * Constants for keys of data array.
     * Each constant represents a column.
     * Identical to the name of the getter in snake case.
     */

    /**
     * Hreflang id
     */
    const HREFLANG_ID = "hreflang_id";

    /**
     * Entity id
     */
    const ENTITY_ID = 'entity_id';

    /**
     * Store id
     */
    const STORE_ID = "store_id";

    /**
     * Entity type (e.g. category, product or cms-page)
     */
    const ENTITY_TYPE = "entity_type";

    /**
     * Hreflang code (e.g. en-us)
     */
    const CODE = "code";

    /**
     * Entity URL
     */
    const URL = "url";

    /**
     * X-default code
     */
    const X_DEFAULT = "x-default";

    /**
     * Get Hreflang id
     *
     * @return int
     */
    public function getHreflangId();

    /**
     * Set Hreflang id
     *
     * @param $hreflangId
     * @return HreflangInterface
     */
    public function setHreflangId($hreflangId);

    /**
     * Get Entity id
     *
     * @return int
     */
    public function getEntityId();

    /**
     * Set Entity id
     *
     * @param $entityId
     * @return HreflangInterface
     */
    public function setEntityId($entityId);

    /**
     * Get Store id
     *
     * @return int
     */
    public function getStoreId();

    /**
     * Set Store id
     *
     * @param $storeId
     * @return HreflangInterface
     */
    public function setStoreId($storeId);

    /**
     * Get entity type
     *
     * @return string
     */
    public function getEntityType();

    /**
     * Set entity type
     *
     * @param $entityType
     * @return HreflangInterface
     */
    public function setEntityType($entityType);

    /**
     * Get code
     *
     * @return string
     */
    public function getCode();

    /**
     * Set code
     *
     * @param $code
     * @return HreflangInterface
     */
    public function setCode($code);

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl();

    /**
     * Set url
     *
     * @param $url
     * @return HreflangInterface
     */
    public function setUrl($url);
}
