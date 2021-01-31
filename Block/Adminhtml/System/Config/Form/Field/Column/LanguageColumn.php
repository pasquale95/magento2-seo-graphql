<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Block\Adminhtml\System\Config\Form\Field\Column;

use Magento\Framework\View\Element\Html\Select;
use Magento\Framework\View\Element\Context;
use Magento\Config\Model\Config\Source\Locale;

/**
 * Class LanguageColumn
 * @package Paskel\Seo\Block\Adminhtml\System\Config\Form\Field\Column
 */
class LanguageColumn extends Select
{
    /**
     * @var Locale
     */
    protected $optionsProvider;

    /**
     * LocaleColumn constructor.
     * @param Context $context
     * @param Locale $optionsProvider
     * @param array $data
     */
    public function __construct(
        Context $context,
        Locale $optionsProvider,
        array $data = []
    ) {
        $this->optionsProvider = $optionsProvider;
        parent::__construct($context, $data);
    }

    /**
     * Set "name" for <select> element
     *
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * Set "id" for <select> element
     *
     * @param $value
     * @return $this
     */
    public function setInputId($value)
    {
        return $this->setId($value);
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->getSourceOptions());
        }
        return parent::_toHtml();
    }

    /**
     * Get option for select
     *
     * @return array
     */
    private function getSourceOptions()
    {
        $locales = $this->optionsProvider->toOptionArray();
        $languages = [];
        foreach ($locales as $locale) {
            $value = $this->removeCountryCode($locale['value']);
            $label = $this->removeCountryName($locale['label']);
            if (!array_key_exists($value,$languages)) {
                $languages[$value] = $label;
            }
        }
        return $languages;
    }

    /**
     * Removes the ending part of the locale, which corresponds
     * to the country code.
     *
     * @param $value
     * @return string
     */
    protected function removeCountryCode($value) {
        return preg_replace("/_.*$/", "", $value);
    }

    /**
     * Removes the countries between round brackets.
     *
     * @param $value
     * @return string
     */
    protected function removeCountryName($value) {
        return preg_replace("/\(.*\)/", "", $value);
    }
}
