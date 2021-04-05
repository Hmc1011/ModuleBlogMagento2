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
            var_dump( $this);

        //    var_dump($this->getConnection());
        if  ($url_key =="1")  return 1;
        else  return false;

        // if  ($url_key=='1') return 1;
    }
}