<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class TwitterCard
 * @package Paskel\Seo\Model\Config\Source
 */
class TwitterCard implements OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray() {
        return [
            [
                'label' => 'Summary Card',
                'value' => 'summary_card'
            ],
            [
                'label' => 'Summary Card with large image',
                'value' => 'summary_large_image'
            ]
        ];
    }
}
