<?php
namespace HoangCong\Blog\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use HoangCong\Blog\Api\Data\PostInterface;

/**
 * Interface PostRepository
 * @package HoangCong\Blog\Api
 */
interface  PostRepositoryInterface
{
    /**
     * @param int $id
     * @return \HoangCong\Blog\Api\Data\PostInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param \HoangCong\Blog\Api\Data\PostInterface $post
     * @return \HoangCong\Blog\Api\Data\PostInterface 
     */
    public function save(PostInterface $post);

  /**
     * @param \VinaiKopp\Kitchen\Api\Data\PostInterface $post
     * @return void
     */
    public function delete(PostInterface $post);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \HoangCong\Blog\Api\Data\PostSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
