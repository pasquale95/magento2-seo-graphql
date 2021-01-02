<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\Hreflang;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Paskel\Seo\Api\Data\HreflangInterface;

/**
 * Class Hreflang
 * @package Paskel\Seo\Model\Hreflang
 */
class Hreflang extends AbstractModel implements IdentityInterface, HreflangInterface
{
    /**
     * Cache tag
     */
    const CACHE_TAG = 'hreflang';

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * @var string
     */
    protected $_eventPrefix = 'hreflang';

    /**
     * Hreflang model constructor
     */
    protected function _construct()
    {
        $this->_init('Paskel\Seo\Model\Hreflang\ResourceModel\Hreflang');
    }

    /**
     * @return string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return array
     */
    public function getDefaultValues()
    {
        return [];
    }

    /**
     * Get Hreflang id
     *
     * @return int
     */
    public function getHreflangId()
    {
        return $this->getData(self::HREFLANG_ID);
    }

    /**
     * Set Hreflang id
     *
     * @param $hreflangId
     * @return Hreflang
     */
    public function setHreflangId($hreflangId)
    {
        return $this->setData(self::HREFLANG_ID, $hreflangId);
    }

    /**
     * Get Entity id
     *
     * @return int
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Set Entity id
     *
     * @param $entityId
     * @return HreflangInterface
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Get Store id
     *
     * @return int
     */
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * Set Store id
     *
     * @param $storeId
     * @return HreflangInterface
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * Get entity type
     *
     * @return string
     */
    public function getEntityType()
    {
        return $this->getData(self::ENTITY_TYPE);
    }

    /**
     * Set entity type
     *
     * @param $entityType
     * @return HreflangInterface
     */
    public function setEntityType($entityType)
    {
        return $this->setData(self::ENTITY_TYPE, $entityType);
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->getData(self::CODE);
    }

    /**
     * Set code
     *
     * @param $code
     * @return HreflangInterface
     */
    public function setCode($code)
    {
        return $this->setData(self::CODE, $code);
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->getData(self::URL);
    }

    /**
     * Set url
     *
     * @param $url
     * @return HreflangInterface
     */
    public function setUrl($url)
    {
        return $this->setData(self::URL, $url);
    }
}
