<?php

namespace HoangCong\Blog\Model\ResourceModel\Comment;

/**
 * Class Collection
 * @package HoangCong\Blog\Model\ResourceModel\Comment;
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'comment_id';

    protected function _construct()
    {
        $this->_init(
            \HoangCong\Blog\Model\Comment::class,
            \HoangCong\Blog\Model\ResourceModel\Comment::class
        );

    }
}