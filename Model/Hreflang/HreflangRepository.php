<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Model\Hreflang;

use Exception;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Paskel\Seo\Api\Data\HreflangInterface;
use Paskel\Seo\Api\Data\HreflangSearchResultsInterface;
use Paskel\Seo\Api\Data\HreflangSearchResultsInterfaceFactory;
use Paskel\Seo\Api\HreflangRepositoryInterface;
use Paskel\Seo\Model\Hreflang\ResourceModel\Hreflang;
use Paskel\Seo\Model\Hreflang\ResourceModel\Hreflang\CollectionFactory as HreflangCollectionFactory;
use Paskel\Seo\Model\Hreflang\ResourceModel\Hreflang\Collection;

/**
 * Class HreflangRepository
 * @package Paskel\Seo\Model
 */
class HreflangRepository implements HreflangRepositoryInterface
{
    /**
     * @var HreflangFactory
     */
    protected $hreflangFactory;

    /**
     * @var HreflangCollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var HreflangSearchResultsInterfaceFactory
     */
    protected $searchResultFactory;

    /**
     * @var Hreflang
     */
    protected $hreflangResourceModel;

    /**
     * @var SearchCriteriaBuilderFactory
     */
    protected $searchCriteriaBuilderFactory;

    /**
     * HreflangRepository constructor.
     *
     * @param HreflangFactory $hreflangFactory
     * @param HreflangCollectionFactory $hreflangCollectionFactory
     * @param HreflangSearchResultsInterfaceFactory $hreflangSearchResultInterfaceFactory
     * @param Hreflang $hreflangResourceModel
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     */
    public function __construct(
        HreflangFactory $hreflangFactory,
        HreflangCollectionFactory $hreflangCollectionFactory,
        HreflangSearchResultsInterfaceFactory $hreflangSearchResultInterfaceFactory,
        Hreflang $hreflangResourceModel,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
    ) {
        $this->hreflangFactory = $hreflangFactory;
        $this->collectionFactory = $hreflangCollectionFactory;
        $this->searchResultFactory = $hreflangSearchResultInterfaceFactory;
        $this->hreflangResourceModel = $hreflangResourceModel;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
    }

    /**
     * Returns hreflang object given the id
     *
     * @param int $id
     * @return HreflangInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id)
    {
        $hreflang = $this->hreflangFactory->create();
        $this->hreflangResourceModel->load($hreflang, $id);
        if (!$hreflang->getId()) {
            throw new NoSuchEntityException(__('Unable to find hreflang with ID "%1"', $id));
        }
        return $hreflang;
    }

    /**
     * Save hreflang object.
     *
     * @param HreflangInterface $hreflang
     * @return HreflangInterface
     * @throws AlreadyExistsException
     */
    public function save(HreflangInterface $hreflang)
    {
        $hreflang->getResource()->save($hreflang);
        return $hreflang;
    }

    /**
     * Delete hreflang object.
     *
     * @param HreflangInterface $hreflang
     * @return void
     * @throws Exception
     */
    public function delete(HreflangInterface $hreflang)
    {
        $hreflang->getResource()->delete($hreflang);
    }

    /**
     * Returns a list of Hreflang results which accomplish the
     * passed search criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return HreflangSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();

        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addSortOrdersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);

        $collection->load();

        return $this->buildSearchResult($searchCriteria, $collection);
    }

    /**
     * Returns the hreflang list belonging to a specific entity whose
     * type and id are passed as args.
     *
     * @param $entityId
     * @param $entityType
     * @return HreflangInterface[]
     * @throws NoSuchEntityException
     */
    public function getByEntity($entityId, $entityType) {
        // create searchCriteria
        $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
        $searchCriteria = $searchCriteriaBuilder
            ->addFilter(
                HreflangInterface::ENTITY_ID,
                $entityId
            )
            ->addFilter(
                HreflangInterface::ENTITY_TYPE,
                $entityType
            )->create();
        $results = $this->getList($searchCriteria)->getItems();
        // Return exception if the item does not exist
        if (empty($results)) {
            throw new NoSuchEntityException(__(
                    'No hreflang defined for the %1 with id "%2". Please generate it.',
                    $entityType, $entityId
                )
            );
        }

        return $results;
    }

    /**
     * Returns hreflang object given the entity id,
     * the entity type and the store id.
     * The returned item must be only one since the
     * combination of such fields is unique at db level.
     *
     * @param $entityId
     * @param $storeId
     * @param $entityType
     * @return HreflangInterface
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function getByStoreAndEntityType($entityId, $storeId, $entityType)
    {
        // create searchCriteria
        $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
        $searchCriteria = $searchCriteriaBuilder
            ->addFilter(
                HreflangInterface::ENTITY_ID,
                $entityId
            )
            ->addFilter(
                HreflangInterface::ENTITY_TYPE,
                $entityType
            )
            ->addFilter(
                HreflangInterface::STORE_ID,
                $storeId
            )->create();
        $results = $this->getList($searchCriteria)->getItems();

        // Return exception if the item does not exist
        if (empty($results)) {
            throw new NoSuchEntityException(__(
                    'Unable to find hreflang for %1 with ID "%2" belonging to store "%3"',
                    $entityType, $entityId, $storeId
                )
            );
        }
        // Expected at most 1 result. If not, raise an exception.
        if (count($results) > 1) {
            throw new LocalizedException(__(
                    'Found unexpected multiple entities satisfying the hreflang constraints. Check the db.',
                    $entityType, $entityId, $storeId
                )
            );
        }
        return reset($results);
    }

    /**
     * Setup the filters defined in the searchCriteria object
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     */
    private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {

            $fields = $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $fields[] = $filter->getField();
                $conditions[] = [$filter->getConditionType() => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    /**
     * Add sort order
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     */
    protected function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }

    /**
     * Adds pagination
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     */
    protected function addPagingToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }

    /**
     * Build the search Results
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     * @return mixed
     */
    protected function buildSearchResult(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $searchResults = $this->searchResultFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
