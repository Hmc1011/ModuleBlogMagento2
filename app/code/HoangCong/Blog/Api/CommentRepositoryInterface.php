<?php
namespace HoangCong\Blog\Api;

use HoangCong\Blog\Api\Data\CommentInterface;

/**
 * Interface CommentRepository
 * @package HoangCong\Blog\Api
 */
interface  CommentRepositoryInterface
{
 
  /**
   * @param int $post_id
   * @return array
   */
  public function getAllCommentOfPost($post_id);

  /**
   * @param CommentInterface $comment
   */
  public function save($comment);
  
  /**
   * @return array
   */
  public function getAllComment();

}
