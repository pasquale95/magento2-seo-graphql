<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Block\Adminhtml\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

/**
 * Class SocialLinks
 * @package Paskel\Seo\Block\Adminhtml\System\Config\Form\Field
 */
class SocialLinks extends AbstractFieldArray
{
    /**
     * Constants defined for keys of columns
     */
    const LINK_COLUMN = 'link';

    /**
     * Render table columns
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            self::LINK_COLUMN,
            [
                'label' => __('Reference Website Link'),
                'class' => 'required-entry'
            ]
        );

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }
}
