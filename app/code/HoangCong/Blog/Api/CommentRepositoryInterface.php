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

    /**
     * @param \HoangCong\Blog\Api\Data\CommentInterface $post
     * @return void
     */
    public function delete(CommentInterface $post);


     /**
     * @param \HoangCong\Blog\Api\Data\CommentInterface $post
     *
     */
    public function enable(CommentInterface $post);

     /**
     * @param \HoangCong\Blog\Api\Data\CommentInterface $post
     * 
     */
    public function disable(CommentInterface $post);

  /**
   * @var int $customerID 
   */
    public function getNewComments($customerID);
}
