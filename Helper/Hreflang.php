<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Helper;

use Magento\CmsUrlRewrite\Model\CmsPageUrlRewriteGenerator;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Paskel\Seo\Api\Data\HreflangInterface;
use Paskel\Seo\Helper\Url as Url;
use Paskel\Seo\Model\Hreflang\HreflangFactory;
use Paskel\Seo\Model\Hreflang\HreflangRepositoryFactory;

/**
 * Class Hreflang
 * @package Paskel\Seo\Helper
 */
class Hreflang extends AbstractHelper
{
    /**
     * @var Url
     */
    protected $urlHelper;

    /**
     * @var HreflangRepositoryFactory
     */
    protected $hreflangRepositoryFactory;

    /**
     * @var HreflangFactory
     */
    protected $hreflangFactory;

    /**
     * @var array
     */
    protected $storesHreflang;

    /**
     * Category constructor.
     * @param Context $context
     * @param Url $urlHelper
     * @param HreflangFactory $hreflangFactory
     * @param HreflangRepositoryFactory $hreflangRepositoryFactory
     * @param Store $storeHelper
     * @throws NoSuchEntityException
     */
    public function __construct(
        Context $context,
        Url $urlHelper,
        HreflangFactory $hreflangFactory,
        HreflangRepositoryFactory $hreflangRepositoryFactory,
        Store $storeHelper
    ) {
        $this->urlHelper = $urlHelper;
        $this->hreflangFactory = $hreflangFactory;
        $this->hreflangRepositoryFactory = $hreflangRepositoryFactory;
        $this->storesHreflang = $storeHelper->getHreflangList();
        parent::__construct($context);
    }

    /**
     * Returns the hreflang link for a specific item and store
     *
     * @param $entityId
     * @param $entityType
     * @param $storeId
     * @return HreflangInterface
     * @throws LocalizedException
     */
    public function getStoreHreflang($entityId, $entityType, $storeId)
    {
        $hreflangRepository = $this->hreflangRepositoryFactory->create();
        try {
            return $hreflangRepository->getByStoreAndEntityType(
                $entityId,
                $storeId,
                $entityType
            );
        } catch (NoSuchEntityException $e) {
            // silent exception, let's just return null if the entity does not exist
        }
        return null;
    }

    /**
     * Returns the list of hreflangs associated with the entity
     *
     * @param $entityId
     * @param $entityType
     * @param $storeId
     * @return array
     */
    public function getStoresHrefLang($entityId, $entityType, $storeId)
    {
        $results = [];

        // check if hreflang is enabled for the entity type
        $useHreflang = $this->scopeConfig->getValue(
            'seo/hreflang/' . str_replace("-", "_", $entityType) . '_hreflang',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        if (!$useHreflang) {
            // do not populate hreflang in graphql if disabled for the entity
            return $results;
        }

        // hreflang is enabled, populate the array
        $hreflangRepository = $this->hreflangRepositoryFactory->create();
        try {
            $hreflangs = $hreflangRepository->getByEntity(
                $entityId,
                $entityType
            );
            foreach ($hreflangs as $hreflang) {
                $results[$hreflang->getCode()] = $hreflang->getUrl();
            }
        } catch (NoSuchEntityException $e) {
            // silent exception, let's just return an empty array if the entity does not exist
        }
        return $results;
    }

    /**
     * Generate the hreflang for a specific entity-store combination.
     * For CMS pages it's necessary its identifier.
     *
     * @param $entityId
     * @param $entityType
     * @param $storeIds
     * @param null|string $entityIdentifier
     * @param null|int $categoryId
     * @throws LocalizedException
     */
    public function generateHreflang($entityId, $entityType, $storeIds, $entityIdentifier = null, $categoryId = null)
    {
        $hreflangRepository = $this->hreflangRepositoryFactory->create();

        foreach ($storeIds as $storeId) {
            if (array_key_exists($storeId, $this->storesHreflang)) {
                // create the hreflang entry for the entity given its id, type and store id
                $requestPath = $this->urlHelper->getRequestPathByUrlRewriter(
                    $entityId,
                    $entityType,
                    $storeId,
                    $categoryId
                );

                // if not requestPath, we cannot generate the hreflang
                if ($requestPath) {
                    try {
                        $hreflang = $hreflangRepository->getByStoreAndEntityType(
                            $entityIdentifier ? $entityIdentifier : $entityId,
                            $storeId,
                            $entityType
                        );
                    } catch (NoSuchEntityException $e) {
                        // create new Hreflang if none has been found through the repository
                        $hreflang = $this->hreflangFactory->create();
                    }

                    // create/update hreflang entry inside the db
                    $hreflang->setEntityId($entityIdentifier ? $entityIdentifier : $entityId);
                    $hreflang->setStoreId($storeId);
                    $hreflang->setEntityType($entityType);
                    $hreflang->setCode($this->storesHreflang[$storeId][Store::HREFLANG]);
                    $hreflang->setUrl(Url::pinchUrl($this->storesHreflang[$storeId][Store::BASE_URL], $requestPath));
                    $hreflangRepository->save($hreflang);
                }
            } elseif ($storeId == 0 and $entityType == CmsPageUrlRewriteGenerator::ENTITY_TYPE) {
                // if a page has storeId = 0, it means that the page is enabled on all store views, thus
                // we must generate the hreflang for all the store views
                $this->generateHreflang($entityId, $entityType, array_keys($this->storesHreflang), $entityIdentifier);
            }
        }
    }
}
