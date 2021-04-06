<?php
namespace HoangCong\Blog\Block;
use HoangCong\Blog\Api\Data\PostInterface;
use HoangCong\Blog\Model\ResourceModel\Post\Collection as PostCollection;

class DetailPost extends \Magento\Framework\View\Element\Template
{

    protected $_postCollectionFactory;

	public function __construct(\Magento\Framework\View\Element\Template\Context $context,
    \HoangCong\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory,
    array $data=[] )
	{
		parent::__construct($context,$data);
        $this->_postCollectionFactory= $postCollectionFactory;
	}

	public function getPost()
	{
        $post_id= $this->_request->getParam('post_id');  
        return  $this->_postCollectionFactory->create()->addFieldToFilter('post_id', ['eq'=> $post_id  ] );
        
        ;
	}
}
