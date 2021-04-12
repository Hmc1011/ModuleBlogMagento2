<?php
namespace HoangCong\Blog\Controller\Comment;
use Magento\Customer\Model\Session;

class Index extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;

	protected \Magento\UrlRewrite\Model\UrlRewrite $k;
	/**
* @var \Magento\UrlRewrite\Model\UrlRewriteFactory
*/
protected $_urlRewriteFactory;
protected $show;
protected $_session;


	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		Session $session )
	{
		$this->_pageFactory = $pageFactory;
		$this->_session= $session;
		return parent::__construct($context);
	}

	public function execute()
	{
        // if (!$this->_request->isAjax()) {
        //     return null;
        // }
	// var_dump($this->_session->getCustomer());
	  var_dump(	$this->_session->isLoggedIn());

	}
}