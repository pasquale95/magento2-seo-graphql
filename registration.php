<?php
/**
 * @author Pasquale Convertini (@Pasquale95)
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'Seo_Hreflang',
    __DIR__
);
