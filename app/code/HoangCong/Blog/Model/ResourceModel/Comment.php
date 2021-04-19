<?php
namespace HoangCong\Blog\Model\ResourceModel;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Model\AbstractModel;

use function PHPUnit\Framework\throwException;

/**
 * Class Comment
 * @package HoangCong\Blog\Model\ResourceModel
 */



class Comment extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{


    CONST COMMENT_POST='hoangcong_blog_comment_post';
    CONST CUSTOMER_COMMENT='hoangcong_blog_customer_comment';
    CONST CUSTOMER_ENTITY='customer_entity';
    CONST BLOG_POST='hoangcong_blog';
    CONST COMMENT='hoangcong_blog_comment';


    protected function _construct()
    {
        $this->_init('hoangcong_blog_comment', 'comment_id');
    }

    public function getAllComment(){
        $connection = $this->getConnection();
        $tableName= $this->_mainTable;

        $sql = $connection->select()->from($tableName, array('*'))
        ->join(self::COMMENT_POST,self::COMMENT_POST.'.comment_id='.$tableName.'.comment_id',['comment_id','post_id'])
        ->join(self::BLOG_POST,self::BLOG_POST.'.post_id='.self::COMMENT_POST.'.post_id',['title'] )
        ->join(self::CUSTOMER_COMMENT,self::CUSTOMER_COMMENT.'.comment_id='.self::COMMENT_POST.'.comment_id','customer_id')
        ->join(self::CUSTOMER_ENTITY,self::CUSTOMER_ENTITY.'.entity_id='.self::CUSTOMER_COMMENT.'.customer_id',['firstname','middlename','lastname','entity_id']);
        $colResult= $connection->fetchAll($sql);

        //    echo ($sql->__toString() );
        // var_dump($colResult);
        return $colResult;

    }

    public function getAllCommentOfPost($post_id)
    {
        $connection = $this->getConnection();
        $tableName= $this->_mainTable;

        $sql = $connection->select()->from($tableName, array('*'))->where($tableName.'.is_active=?',1)
        ->join(self::COMMENT_POST,self::COMMENT_POST.'.comment_id='.$tableName.'.comment_id AND  '.self::COMMENT_POST.'.post_id='.$post_id  )
        ->join(self::CUSTOMER_COMMENT,self::CUSTOMER_COMMENT.'.comment_id='.self::COMMENT_POST.'.comment_id','customer_id')
        ->join(self::CUSTOMER_ENTITY,self::CUSTOMER_ENTITY.'.entity_id='.self::CUSTOMER_COMMENT.'.customer_id','lastname')
        ;
        $colResult= $connection->fetchAll($sql);
        //    echo ($sql->__toString() );
        // var_dump($colResult);
        return $colResult;
        
    }

    public function enable($comment)
    {
            $comment->setIsActive(true);
            $comment->saveWhenUpdate();
    } 

    public function disable($comment)
    {
            $comment->setIsActive(false);
            $comment->saveWhenUpdate();
    } 

    public function saveCustomerAndPost($customer_id,$post_id,$comment_id)
    {

            $connection = $this->getConnection();
            $connection->insertArray(self::COMMENT_POST,['comment_id','post_id'],[[$comment_id,$post_id]] );
            $connection->insertArray(self::CUSTOMER_COMMENT,['customer_id','comment_id'],[[$customer_id,$comment_id]]);
    
    }

    public function getNewComments($customerID,$postID)
    {
        $connection = $this->getConnection();

        $sql = $connection->select()->from(self::CUSTOMER_COMMENT, array('*'))
        ->join(self::COMMENT,self::COMMENT.'.comment_id='.self::CUSTOMER_COMMENT.'.comment_id AND '.self::CUSTOMER_COMMENT.'.customer_id='.$customerID .' AND '.self::COMMENT.'.is_active=0' ,['comment','is_active'])
        ->join(self::COMMENT_POST,self::COMMENT_POST.'.comment_id='.self::CUSTOMER_COMMENT.'.comment_id','post_id')
        ;

        $k= $sql->__toString();
        $colResult= $connection->fetchAll($sql);

        return $colResult;
    }
}
