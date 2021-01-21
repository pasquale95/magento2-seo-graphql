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
 * Interface SchemaOrgInterface
 * @package Paskel\Seo\Api\Data
 */
interface SchemaOrgInterface
{
    const SCHEMA_CONTEXT = "https://schema.org";

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getScript();
}