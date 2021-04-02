<?php
 
namespace HoangCong\Blog\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use HoangCong\Blog\Api\Data\PostExtensionInterface;
use HoangCong\Blog\Api\Data\PostInterface;

 
class Post extends AbstractExtensibleModel implements PostInterface
{
    const NAME = 'name';
    const INGREDIENTS = 'ingredients';
    const IMAGE_URLS = 'image_urls';
 
    protected function _construct()
    {
        $this->_init(ResourceModel\Post::class);
    }

    public function getTitle()
    {
        return $this->_getData(self::TITLE);
    }
    
    public function getContent(){}
     public function getCreationTime(){}
      public function getUpdateTime(){}
      public function getUrlKey(){}

      public function isActive(){}
    public function setUrlKey($url_key){}
        public function getUrl(){}
        public function setTitle($title){}
        public function setContent($content){}
        public function setCreationTime($creationTime){}
        public function setUpdateTime($updateTime){
        }
        public function setIsActive($isActive){}

 
    // public function setName($name)
    // {
    //     $this->setData(self::NAME);
    // }
 
    // public function getIngredients()
    // {
    //     return $this->_getData(self::INGREDIENTS);
    // }

}