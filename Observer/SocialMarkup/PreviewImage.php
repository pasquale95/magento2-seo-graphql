<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Observer\SocialMarkup;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Paskel\Seo\Api\Data\SocialMarkupInterface;

/**
 * Class PreviewImage
 * @package Paskel\Seo\Observer\SocialMarkup
 */
class PreviewImage implements ObserverInterface
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * PreviewImage constructor.
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    /**
     * Retrieve image url and save it in the db.
     *
     * @param Observer $observer
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        // get page
        $page = $observer->getEvent()->getData('page');
        // save only URL of image
        $social_markup_image = $page->getData(SocialMarkupInterface::IMAGE_FIELD_DB);
        if ($social_markup_image) {
            if (is_string($social_markup_image)) {
                $url = $social_markup_image;
            } else {
                $url = $social_markup_image[0]['url'];
            }
            // url is not complete, we need to complete it
            if (substr($url, 0, 1) == "/") {
                $url = rtrim($this->storeManager->getStore()->getBaseUrl(), "/")
                    . "/"
                    . ltrim($url, "/");
            }
            $page->setData(SocialMarkupInterface::IMAGE_FIELD_DB, $url);
        }
    }
}
