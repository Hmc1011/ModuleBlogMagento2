<?php
namespace HoangCong\Blog\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use HoangCong\Blog\Api\Data\CommentInterface;

/**
 * Interface CommentRepository
 * @package HoangCong\Blog\Api
 */
interface  CommentRepositoryInterface
{
 
  /**
   * @var int $post_id
   */
  public function getAllCommentOfPost($post_id);
  
}
