<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\SocialMarkup;

use Paskel\Seo\Api\Data\OpenGraphInterface;

/**
 * Class AbstractOpenGraph
 * @package Paskel\Seo\Model\SocialMarkup
 */
abstract class AbstractOpenGraph extends AbstractSocialMarkup implements OpenGraphInterface
{
    /**
     * @inheritDoc
     */
    public function setType($type) {
        $this->addTag(self::TYPE, $type);
    }

    /**
     * @inheritDoc
     */
    public function setLocale($locale) {
        $this->addTag(self::LOCALE, $locale);
    }

    /**
     * @inheritDoc
     */
    public function setSitename($sitename) {
        $this->addTag(self::SITENAME, $sitename);
    }

    /**
     * @inheritDoc
     */
    public function setUrl($url) {
        $this->addTag(self::URL, $url);
    }

    /**
     * @inheritDoc
     */
    public function setTitle($title) {
        $this->addTag(self::TITLE, $title);
    }

    /**
     * @inheritDoc
     */
    public function setDescription($description) {
        $this->addTag(self::DESCRIPTION, $description);
    }

    /**
     * @inheritDoc
     */
    public function setImage($image) {
        $this->addTag(self::IMAGE, $image);
    }

    /**
     * Returns the specific property-content properly wrapped
     *
     * @param $property
     * @param $content
     */
    public function addTag($property, $content) {
        if (!($this->socialMarkupHelper->hideUnsetPropertiesInGraphQl() and $content == null)) {
            array_push($this->tags,
                [
                    self::TAG_PROPERTY => $property,
                    self::TAG_CONTENT => $content
                ]
            );
        }
    }

    /**
     * Add a new tag in the tags array.
     *
     * @param int|null $storeId
     */
    public function setSitenameByStore($storeId = null) {
        $sitename = $this->socialMarkupHelper->getSitename($storeId);
        if ($sitename) {
            $this->setSitename($this->socialMarkupHelper->getSitename($storeId));
        } else {
            $this->setSitename(self::SITENAME_VALUE);
        }
    }
}
