<?php

namespace HoangCong\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class NewPost extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    public function execute()
    {
        
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Admin HoangCong_Blog: Create a new post'));
        return $resultPage;
    }
    protected function _isAllowed()
    {   
            return $this->_authorization->isAllowed('HoangCong_Blog::save');
    }
}