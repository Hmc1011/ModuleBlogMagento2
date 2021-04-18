<?php

namespace HoangCong\Blog\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use HoangCong\Blog\Api\Data\PostExtensionInterface;
use HoangCong\Blog\Api\Data\PostInterface;
use \Magento\Framework\App\Config\ScopeConfigInterface;

class Post extends AbstractExtensibleModel implements PostInterface
{
    // const NAME = 'name';
    // const INGREDIENTS = 'ingredients';
    // const IMAGE_URLS = 'image_urls';
    

    protected function _construct()
    {
        $this->_init(ResourceModel\Post::class);
    }
    
    public function getTitle()
    {
        return $this->_getData(self::TITLE);
    }
    public function getContent()
    {
        return $this->_getData(self::CONTENT);
    }
    public function getCreationTime()
    {
        return $this->_getData(self::CREATION_TIME);
    }
    public function getUpdateTime()
    {
        return $this->_getData(self::UPDATE_TIME);
    }
    public function getUrlKey()
    {
        return $this->_getData(self::URL_KEY);
    }

    public function isActive()
    {
        return boolval($this->_getData(self::IS_ACTIVE));
    }

    public function setUrlKey($url_key)
    {
        $this->setData(self::URL_KEY,$url_key);
    }
    public function getUrl()
    {
            return  $this->_getData(self::URL_KEY).".html" ;
    }
    public function setTitle($title)
    {
        $this->setData(self::TITLE,$title);
    }
    public function setContent($content)
    {
         $this->setData(self::CONTENT,$content);
    }
    public function setCreationTime($creationTime)
    {
        $this->setData(self::CREATION_TIME,$creationTime);
    }
    public function setUpdateTime($updateTime)
    {
        $this->setData(self::UPDATE_TIME,$updateTime);
    }
    public function setIsActive($isActive)
    {
        if  ($isActive) $this->setData(self::IS_ACTIVE, self::STATUS_ENABLED );
        else  $this->setData(self::IS_ACTIVE,self::STATUS_DISABLED);
    }
    public function checkUrlKey($url_key)
    {
        return $this->_getResource()->checkUrlKey($url_key);      
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    // public function setName($name)
    // {
    //     $this->setData(self::NAME);
    // }

    // public function getIngredients()
    // {
    //     return $this->_getData(self::INGREDIENTS);
    // }

}
