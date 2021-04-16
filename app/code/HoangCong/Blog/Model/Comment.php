<?php

namespace HoangCong\Blog\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use HoangCong\Blog\Api\Data\CommentExtensionInterface;
use HoangCong\Blog\Api\Data\CommentInterface;
use Magento\CatalogImportExport\Model\Import\Proxy\Product\ResourceModel;
use \Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject\IdentityInterface;

class Comment extends AbstractExtensibleModel implements CommentInterface,IdentityInterface
{
    const CACHE_TAG = 'hoangcong_blog_post';

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
        $this->_init(\Hoangcong\Blog\Model\ResourceModel\Comment::class);
    }

    public function getPost()
    {
        /**@var  \HoangCong\Blog\Model\Post $post*/
        $post=  \Magento\Framework\App\ObjectManager::getInstance()
        ->create(\HoangCong\Blog\Model\Post::class);
        $post->load($this->getData('post_id'))  ;
        return $post;
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getPost()->getId()];
    }

    public function getAllCommentOfPost($post_id)
    {
         return   $this->getResource()->getAllCommentOfPost($post_id);
    }
    public function getAllComment()
    {
        return   $this->getResource()->getAllComment();
    }

    public function setContentComment($content){
         $this->setData(self::COMMENT_CONTENT,$content);
    }

    public function setCreationTime($date){
        $this->setData(self::CREATION_TIME,$date);
   }
    public function setIsActive($isActive)
    {
        if  ($isActive) $this->setData(self::IS_ACTIVE, self::STATUS_ENABLED );
        else  $this->setData(self::IS_ACTIVE,self::STATUS_DISABLED);
    }

    public function saveWhenUpdate()
    {
        parent::save();
    }
    public function save(){
            parent::save();
            $comment_id= $this->getData(self::COMMENT_ID);
            $this->getResource()->saveCustomerAndPost($this->customer_id,$this->post_id,$comment_id);
            return true;

    }
}
