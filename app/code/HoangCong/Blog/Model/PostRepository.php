<?php
 
namespace HoangCong\Blog\Model;
 
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use HoangCong\Blog\Api\Data\PostInterface;
use HoangCong\Blog\Api\Data\PostSearchResultInterface;
use HoangCong\Blog\Api\Data\PostSearchResultInterfaceFactory;
use HoangCong\Blog\Api\PostRepositoryInterface;
use HoangCong\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;
use HoangCong\Blog\Model\ResourceModel\Post\Collection;
 
class PostRepository implements PostRepositoryInterface
{
    /**
     * @var PostFactory
     */
    private $postFactory;
 
    /**
     * @var PostCollectionFactory
     */
    private $postCollectionFactory;
 
    /**
     * @var PostSearchResultInterfaceFactory
     */
    private $searchResultFactory;
 
    public function __construct(
        PostFactory $postFactory,
        PostCollectionFactory $postCollectionFactory,
        PostSearchResultInterfaceFactory $postSearchResultInterfaceFactory
    ) {
        $this->postFactory = $postFactory;
        $this->postCollectionFactory = $postCollectionFactory;
        $this->searchResultFactory = $postSearchResultInterfaceFactory;
    }
 
    // ... getById, save and delete methods listed above ...
 
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
 
        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addSortOrdersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);
 
        $collection->load();
 
        return $this->buildSearchResult($searchCriteria, $collection);
    }
 
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
 
    private function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }
 
    private function addPagingToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }
 
    private function buildSearchResult(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $searchResults = $this->searchResultFactory->create();
 
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
 
        return $searchResults;
    }
}