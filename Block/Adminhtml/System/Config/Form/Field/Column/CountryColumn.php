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
use Magento\Directory\Model\Config\Source\Country;

/**
 * Class CountryColumn
 * @package Paskel\Seo\Block\Adminhtml\System\Config\Form\Field\Column
 */
class CountryColumn extends Select
{
    /**
     * @var Country
     */
    protected $optionsProvider;

    /**
     * CountryColumn constructor.
     * @param Context $context
     * @param Country $optionsProvider
     * @param array $data
     */
    public function __construct(
        Context $context,
        Country $optionsProvider,
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
        return $this->optionsProvider->toOptionArray();
    }
}
