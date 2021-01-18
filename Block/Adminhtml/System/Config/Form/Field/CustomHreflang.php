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
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Paskel\Seo\Block\Adminhtml\System\Config\Form\Field\Column\CountryColumn;
use Paskel\Seo\Block\Adminhtml\System\Config\Form\Field\Column\LanguageColumn;

/**
 * Class CustomHreflang
 * @package Paskel\Seo\Block\Adminhtml\System\Config\Form\Field
 */
class CustomHreflang extends AbstractFieldArray
{
    /**
     * Constants defined for keys of columns
     */
    const COUNTRY_COLUMN = 'country';
    const LANGUAGE_COLUMN = 'language';

    /**
     * @var CountryColumn
     */
    protected $countryColumnRenderer;

    /**
     * @var LanguageColumn
     */
    protected $languageColumnRenderer;

    /**
     * Render columns in table.
     *
     * @throws LocalizedException
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            self::LANGUAGE_COLUMN,
            [
                'label' => __('Language'),
                'renderer' => $this->getLanguageRenderer()
            ]
        );
        $this->addColumn(
            self::COUNTRY_COLUMN,
            [
                'label' => __('Country'),
                'renderer' => $this->getCountryRenderer()
            ]
        );

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * Prepare existing row data object
     *
     * @param DataObject $row
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row)
    {
        $options = [];

        if ($row->getData(self::COUNTRY_COLUMN) != null) {
            $optionHash = $this->getCountryRenderer()->calcOptionHash($row->getData(self::COUNTRY_COLUMN));
            $options['option_' . $optionHash] = 'selected="selected"';
        }
        if ($row->getData(self::LANGUAGE_COLUMN) != null) {
            $optionHash = $this->getLanguageRenderer()->calcOptionHash($row->getData(self::LANGUAGE_COLUMN));
            $options['option_' . $optionHash] = 'selected="selected"';
        }
        $this->_addButtonLabel = __('Add');

        $row->setData('option_extra_attrs', $options);
    }

    /**
     * @return CountryColumn
     * @throws LocalizedException
     */
    protected function getCountryRenderer()
    {
        if (!$this->countryColumnRenderer) {
            $this->countryColumnRenderer = $this->getLayout()->createBlock(
                CountryColumn::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->countryColumnRenderer->setClass('admin__control-select');
        }
        return $this->countryColumnRenderer;
    }

    /**
     * @return LanguageColumn
     * @throws LocalizedException
     */
    protected function getLanguageRenderer()
    {
        if (!$this->languageColumnRenderer) {
            $this->languageColumnRenderer = $this->getLayout()->createBlock(
                LanguageColumn::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->languageColumnRenderer->setClass('admin__control-select');
        }
        return $this->languageColumnRenderer;
    }
}
