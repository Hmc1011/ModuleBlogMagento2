<?php
namespace HoangCong\Blog\Api;

/**
 * Interface PostRepository
 * @package HoangCong\Blog\Api
 */
interface  PostRepositoryInterface
{
    /**
     * @param int $id
     * @return \ViMagento\CustomApi\Api\Data\CustomInterface
     */
    public function getById($id);

    /**
     * @param \ViMagento\CustomApi\Api\Data\CustomInterface $vimagento
     * @return \ViMagento\CustomApi\Api\Data\CustomInterface
     */
    public function save(CustomInterface $vimagento);

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById($id);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \ViMagento\CustomApi\Api\Data\CustomSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
