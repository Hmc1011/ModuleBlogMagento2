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
     * @param int $id
     * @return bool
     */
    public function deleteById($id);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \ViMagento\CustomApi\Api\Data\PostSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
