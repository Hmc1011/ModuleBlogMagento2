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
    const IS_ACTIVE='is_active';
    const STATUS_ENABLED=1;
    const STATUS_DISABLED=0;
    const CREATION_TIME='creation_time';


    /**
     * @return int
     */
     public function getId();

     /**
      * @var int $post_id
      * @return \HoangCong\Blog\Model\ResourceModel\Comment\Collection
      */
    public  function getAllCommentOfPost($post_id);

    /**
     * @var \DateTime $date
     */
    public function setCreationTime($date);

}
