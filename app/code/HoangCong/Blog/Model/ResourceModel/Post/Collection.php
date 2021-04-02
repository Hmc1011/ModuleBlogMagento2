<?php

namespace HoangCong\Blog\Model\ResourceModel\Post;

/**
 * Class Collection
 * @package HoangCong\Blog\Model\ResourceModel\Post;
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'post_id';

    protected function _construct()
    {
        $this->_init(
            \HoangCong\Blog\Model\Post::class,
            \HoangCong\Blog\Model\ResourceModel\Post::class
        );
    }
}