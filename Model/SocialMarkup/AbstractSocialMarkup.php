<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\SocialMarkup;

use Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\Image\Placeholder as PlaceholderProvider;
use Magento\Framework\Exception\LocalizedException;
use Magento\StoreGraphQl\Model\Resolver\Store\StoreConfigDataProvider;
use Paskel\Seo\Api\Data\OpenGraphInterface;
use Paskel\Seo\Api\Data\SocialMarkupInterface;
use Paskel\Seo\Helper\Hreflang as HreflangHelper;
use Paskel\Seo\Helper\SocialMarkup as SocialMarkupHelper;

/**
 * Class AbstractSocialMarkup
 * @package Paskel\Seo\Model\SocialMarkup
 */
abstract class AbstractSocialMarkup implements SocialMarkupInterface
{

    /**
     * @var array
     */
    protected $tags = [];

    /**
     * @var StoreConfigDataProvider
     */
    protected $storeConfigDataProvider;

    /**
     * @var HreflangHelper
     */
    protected $hreflangHelper;

    /**
     * @var PlaceholderProvider
     */
    protected $placeholderProvider;

    /**
     * @var SocialMarkupHelper
     */
    protected SocialMarkupHelper $socialMarkupHelper;

    /**
     * AbstractSocialMarkup constructor.
     *
     * @param StoreConfigDataProvider $storeConfigsDataProvider
     * @param HreflangHelper $hreflangHelper
     * @param PlaceholderProvider $placeholderProvider
     * @param SocialMarkupHelper $socialMarkupHelper
     */
    public function __construct(
        StoreConfigDataProvider $storeConfigsDataProvider,
        HreflangHelper $hreflangHelper,
        PlaceholderProvider $placeholderProvider,
        SocialMarkupHelper $socialMarkupHelper
    ) {
        $this->storeConfigDataProvider = $storeConfigsDataProvider;
        $this->placeholderProvider = $placeholderProvider;
        $this->hreflangHelper = $hreflangHelper;
        $this->socialMarkupHelper = $socialMarkupHelper;
    }
}
