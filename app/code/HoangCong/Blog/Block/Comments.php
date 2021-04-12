<?php
namespace HoangCong\Blog\Block;
use HoangCong\Blog\Api\CommentRepositoryInterface;

class Comments extends \Magento\Framework\View\Element\Template
{

    protected $_commentRepository;

	public function __construct(\Magento\Framework\View\Element\Template\Context $context,
    CommentRepositoryInterface $commentRepository,
    array $data=[] )
	{
		parent::__construct($context,$data);
        $this->_commentRepository= $commentRepository;
	}

	public function getComments()
	{
		$post_id= $this->_request->getParam('post_id');  
        return $this->_commentRepository->getAllCommentOfPost($post_id);
	}
}
