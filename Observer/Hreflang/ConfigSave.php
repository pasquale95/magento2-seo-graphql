<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Observer\Hreflang;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Paskel\Seo\Console\Hreflang;

/**
 * Class ConfigSave
 * @package Paskel\Seo\Observer\Hreflang
 */
class ConfigSave implements ObserverInterface
{
    /**
     * @var Hreflang
     */
    protected Hreflang $hreflangGenerator;

    /**
     * ConfigSave constructor.
     * @param Hreflang $hreflangGenerator
     */
    public function __construct(
        Hreflang $hreflangGenerator
    ) {
        $this->hreflangGenerator = $hreflangGenerator;
    }

    /**
     * Regenerate the hreflang when the seo configs
     * have been updated.
     *
     * @param Observer $observer
     * @throws LocalizedException
     */
    public function execute(Observer $observer) {
        $this->hreflangGenerator->generateHreflangs();
    }
}
