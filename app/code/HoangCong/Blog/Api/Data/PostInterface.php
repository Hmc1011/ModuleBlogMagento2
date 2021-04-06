<?php
namespace HoangCong\Blog\Api\Data;

use DateTime;
use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface Post
 * @package HoangCong\Blog\Api
 */
interface  PostInterface extends ExtensibleDataInterface
{
    const POST_ID='post_id';
    const URL_KEY='url_key';
    const TITLE='title';
    const CONTENT='post';
    const CREATION_TIME='creation_time';
    const UPDATE_TIME='update_time';
    const IS_ACTIVE='is_active';

    /**
     * @return int
     */
     public function getId();

    /**
     * @return int
     */

    public function getUrlKey();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getContent();
    
    /**
     * @return \DateTime
     */

     public function getCreationTime();
     
     /**
      * @return \DateTime
      */
      public function getUpdateTime();

      /**
       * @return boolean
       */
      public function isActive();

      /**
       * @param int $id
       */
      public function setId($id);
      
      /**
       * @param int $url_key
       */
        public function setUrlKey($url_key);
        
        /**
         * @return string
         */
        public function getUrl();

        /**
         * @param string $title
         */
        public function setTitle($title);

        /**
         * @param string $content
         */
        public function setContent($content);

        /**
         * @param \DateTime
         */
        public function setCreationTime($creationTime);

        /**
         * @param \DateTime $updateTime
         */
        public function setUpdateTime($updateTime);
        
        /**
         * @param boolean $isActive
         */
        public function setIsActive($isActive);
        
}
