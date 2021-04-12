<?php
namespace HoangCong\Blog\Model\ResourceModel;

use Magento\Framework\App\ObjectManager;

/**
 * Class Comment
 * @package HoangCong\Blog\Model\ResourceModel
 */



class Comment extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{


    CONST COMMENT_POST='hoangcong_blog_comment_post';
    CONST CUSTOMER_COMMENT='hoangcong_blog_customer_comment';
    CONST CUSTOMER_ENTITY='customer_entity';


    protected function _construct()
    {
        $this->_init('hoangcong_blog_comment', 'comment_id');
    }
    public function getAllCommentOfPost($post_id)
    {
        $connection = $this->getConnection();
        $tableName= $this->_mainTable;

        $sql = $connection->select()->from($tableName, array('*'))
        ->join(self::COMMENT_POST,self::COMMENT_POST.'.comment_id='.$tableName.'.comment_id AND  '.self::COMMENT_POST.'.post_id='.$post_id  )
        ->join(self::CUSTOMER_COMMENT,self::CUSTOMER_COMMENT.'.comment_id='.self::COMMENT_POST.'.comment_id','customer_id')
        ->join(self::CUSTOMER_ENTITY,self::CUSTOMER_ENTITY.'.entity_id='.self::CUSTOMER_COMMENT.'.customer_id','lastname')
        ;
        $colResult= $connection->fetchAll($sql);
        //    echo ($sql->__toString() );
        // var_dump($colResult);
        return $colResult;
        
    }
}
