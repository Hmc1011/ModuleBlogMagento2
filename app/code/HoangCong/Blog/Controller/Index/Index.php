<?php
namespace HoangCong\Blog\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory)
	{
		$this->_pageFactory = $pageFactory;
		return parent::__construct($context);
	}

	public function execute()
	{
		$this->_objectManager->get('Psr\Log\LoggerInterface')->debug('toi la cong');
			return $this->_pageFactory->create();
	}
}