<?php
namespace HoangCong\Blog\Model\ResourceModel;

/**
 * Class Post
 * @package HoangCong\Blog\Model\ResourceModel
 */
class Post extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('hoangcong_blog', 'post_id');
    }
}