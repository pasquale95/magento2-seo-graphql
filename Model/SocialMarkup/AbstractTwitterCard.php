<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\SocialMarkup;

use Paskel\Seo\Api\Data\TwitterCardInterface;

/**
 * Class AbstractTwitterCard
 * @package Paskel\Seo\Model\SocialMarkup
 */
abstract class AbstractTwitterCard extends AbstractSocialMarkup implements TwitterCardInterface
{
    /**
     * @inheritDoc
     */
    public function setCard($card) {
        $this->addTag(self::CARD, $card);
    }

    /**
     * @inheritDoc
     */
    public function setSite($site) {
        $this->addTag(self::SITE, $site);
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
     * Add a new tag in the tags array.
     *
     * @param $name
     * @param $content
     */
    public function addTag($name, $content) {
        if (!($this->socialMarkupHelper->hideUnsetPropertiesInGraphQl() and $content == null)) {
            array_push($this->tags,
                [
                    self::TAG_NAME => $name,
                    self::TAG_CONTENT => $content
                ]
            );
        }
    }
}
