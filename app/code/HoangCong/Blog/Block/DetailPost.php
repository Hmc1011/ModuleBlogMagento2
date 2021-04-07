<?php
namespace HoangCong\Blog\Block;
use HoangCong\Blog\Api\PostRepositoryInterface;

class DetailPost extends \Magento\Framework\View\Element\Template
{

    protected $_postRepository;

	public function __construct(\Magento\Framework\View\Element\Template\Context $context,
    PostRepositoryInterface $postRepository,
    array $data=[] )
	{
		parent::__construct($context,$data);
        $this->_postRepository= $postRepository;
	}

	public function getPost()
	{
        $post_id= $this->_request->getParam('post_id');  
        $post= $this->_postRepository->getById($post_id);
        return $post;
	}
}
