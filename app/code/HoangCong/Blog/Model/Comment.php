<?php

namespace HoangCong\Blog\Model;

use Elasticsearch\Endpoints\Ml\GetDatafeeds;
use Magento\Framework\Model\AbstractExtensibleModel;
use HoangCong\Blog\Api\Data\CommentExtensionInterface;
use HoangCong\Blog\Api\Data\CommentInterface;
use \Magento\Framework\App\Config\ScopeConfigInterface;

class Comment extends AbstractExtensibleModel implements CommentInterface
{
    

    protected function _construct()
    {
        $this->_init(ResourceModel\Comment::class);
    }
    
    public function getAllCommentOfPost($post_id)
    {
         return   $this->getResource()->getAllCommentOfPost($post_id);
    }
}
