<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\SchemaOrg;

use Paskel\Seo\Api\Data\SchemaOrgInterface;


abstract class AbstractSchemaOrg implements SchemaOrgInterface
{
    /**
     * Returns the schema properties wrapped in an associative array.
     *
     * @return array
     */
    abstract public function getProperties();

    /**
     * Returns true if the schema is enabled.
     *
     * @return boolean
     */
    abstract public function isEnabled();

    /**
     * @inheritdoc
     */
    public function getScript() {
        // check that this schema is enabled.
        if (!$this->isEnabled()) {
            return null;
        }
        $script = '<script type="application/ld+json">{';
        $properties = $this->getProperties();
        foreach ($properties as $name=>$content) {
            if (!empty(ltrim(rtrim($content, '"'), '"'))) {
                $script .= '"' . $name . '": ' . $content . ',';
            }
        }
        return rtrim($script, ",")
            . '}'
            . '</script>';
    }
}
