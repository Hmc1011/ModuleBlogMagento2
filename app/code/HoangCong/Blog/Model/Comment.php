<?php

namespace HoangCong\Blog\Model;

use Elasticsearch\Endpoints\Ml\GetDatafeeds;
use Magento\Framework\Model\AbstractExtensibleModel;
use HoangCong\Blog\Api\Data\CommentExtensionInterface;
use HoangCong\Blog\Api\Data\CommentInterface;
use \Magento\Framework\App\Config\ScopeConfigInterface;

class Comment extends AbstractExtensibleModel implements CommentInterface
{

    /**
   * @var int $customer_id
   */
  public $customer_id;
    
  /**
   * @var int $post_id
   */
  public $post_id;

    protected function _construct()
    {
        $this->_init(ResourceModel\Comment::class);
    }
    
    public function getAllCommentOfPost($post_id)
    {
         return   $this->getResource()->getAllCommentOfPost($post_id);
    }

    public function setContentComment($content){
         $this->setData(self::COMMENT_CONTENT,$content);
    }

    public function save(){
            parent::save();
            $comment_id= $this->getData(self::COMMENT_ID);
            echo $comment_id;
            $this->getResource()->saveCustomerAndPost($this->customer_id,$this->post_id,$comment_id);
            return true;

    }
}
