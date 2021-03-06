<?php
namespace HoangCong\Blog\Model\ResourceModel;

use Magento\Framework\App\ObjectManager;

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

    public function enable($post)
    {
            $post->setIsActive(true);
            $post->save();
    } 

    public function disable($post)
    {
            $post->setIsActive(false);
            $post->save();
    } 
    public function checkUrlKey($url_key)
    {
        ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug($url_key);

            $connection = $this->getConnection();
            $tableName= $this->_mainTable;

            $sql = $connection->select()->from($tableName, array('*'))
            ->where('url_key=?',$url_key);
            $colResult= $connection->fetchCol($sql);

            // var_dump($colResult);
            // echo ($sql->__toString() );
            ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug($sql->__toString());
            if  (count($colResult)==0) return false;
            return $colResult[0];

    }
}