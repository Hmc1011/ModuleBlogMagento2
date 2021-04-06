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
    public function checkUrlKey($url_key)
    {
            $connection = $this->getConnection();
            $tableName= $this->_mainTable;

            $sql = $connection->select()->from($tableName, array('url_key'))
            ->where('url_key=?',$url_key);
            $colResult= $connection->fetchCol($sql);
            // var_dump($colResult);
            // echo ($sql->__toString() );

            if  (count($colResult)==0) return false;
            return true;

    }
}