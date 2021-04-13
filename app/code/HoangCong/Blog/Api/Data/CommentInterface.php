<?php
namespace HoangCong\Blog\Api\Data;

use DateTime;
use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface Comment
 * @package HoangCong\Blog\Api
 */
interface  CommentInterface extends ExtensibleDataInterface
{
    const COMMENT_ID='comment_id';
    const COMMENT_CONTENT='comment';
  

    /**
     * @return int
     */
     public function getId();

     /**
      * @var int $post_id
      * @return \HoangCong\Blog\Model\ResourceModel\Comment\Collection
      */
    public  function getAllCommentOfPost($post_id);


}
