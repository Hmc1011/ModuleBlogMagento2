<?php

namespace HoangCong\Blog\Model\ResourceModel\Comment;

/**
 * Class Collection
 * @package HoangCong\Blog\Model\ResourceModel\Comment;
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'comment_id';


    CONST COMMENT_POST='hoangcong_blog_comment_post';
    CONST CUSTOMER_COMMENT='hoangcong_blog_customer_comment';
    CONST CUSTOMER_ENTITY='customer_entity';
    CONST BLOG_POST='hoangcong_blog';

    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->_init(
            \HoangCong\Blog\Model\Comment::class,
            \HoangCong\Blog\Model\ResourceModel\Comment::class
        );
        parent::__construct(
            $entityFactory, $logger, $fetchStrategy, $eventManager, $connection,
            $resource
        );
        $this->storeManager = $storeManager;
    }


    protected function _construct()
    {
        $this->_init(
            \HoangCong\Blog\Model\Comment::class,
            \HoangCong\Blog\Model\ResourceModel\Comment::class
        );

    }

    protected function _initSelect()
    {
        parent::_initSelect();
        $tableName='main_table';
        $debug= \Magento\Framework\App\ObjectManager::getInstance()->create(\Psr\Log\LoggerInterface::class);
        $debug->debug($this->getSelect()->__toString());
        
            $this->getSelect()
            ->join(self::COMMENT_POST,self::COMMENT_POST.'.comment_id='.$tableName.'.comment_id',['post_id'])
            ->join(self::BLOG_POST,self::BLOG_POST.'.post_id='.self::COMMENT_POST.'.post_id',['title'] )
            ->join(self::CUSTOMER_COMMENT,self::CUSTOMER_COMMENT.'.comment_id='.self::COMMENT_POST.'.comment_id',['customer_id'])
            ->join(self::CUSTOMER_ENTITY,self::CUSTOMER_ENTITY.'.entity_id='.self::CUSTOMER_COMMENT.'.customer_id',['firstname','middlename','lastname','entity_id'])
            
    ;
            return $this;
    }

    
  /**
     * Build sql where condition part
     *
     * @param   string|array $field
     * @param   null|string|array $condition
     * @return  string
     */
    protected function _translateCondition($field, $condition)
    {
        $field = 'main_table.'.$this->_getMappedField($field);
        return $this->_getConditionSql($this->getConnection()->quoteIdentifier($field), $condition);
    }

}

