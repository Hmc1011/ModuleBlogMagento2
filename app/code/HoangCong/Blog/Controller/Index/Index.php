<?php
namespace HoangCong\Blog\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;

	protected \Magento\UrlRewrite\Model\UrlRewrite $k;
	/**
* @var \Magento\UrlRewrite\Model\UrlRewriteFactory
*/
protected $_urlRewriteFactory;
protected $show;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory,
		$show=''
		)
	{
		$this->show= $show;
		$this->_pageFactory = $pageFactory;
		$this->_urlRewriteFactory = $urlRewriteFactory;

		return parent::__construct($context);
	}

	public function execute()
	{
		$urlRewriteModel = $this->_urlRewriteFactory->create();
/* set current store id */
$urlRewriteModel->setStoreId(1);
/* this url is not created by system so set as 0 */
$urlRewriteModel->setIsSystem(0);
/* unique identifier - set random unique value to id path */
// $urlRewriteModel->setIdPath(rand(1, 100000));
/* set actual url path to target path field */
$urlRewriteModel->setTargetPath("blog");
/* set requested path which you want to create */
$urlRewriteModel->setRequestPath("blog");
/* set current store id */
try {
	$urlRewriteModel->save();
}
 catch (\Exception $e) 
 {      	}

		$this->_objectManager->get('Psr\Log\LoggerInterface')->debug('toi la cong');
	echo($this->show);
		return $this->_pageFactory->create();
	}
}