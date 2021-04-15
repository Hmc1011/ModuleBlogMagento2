<?php

namespace HoangCong\Blog\Controller\Comment;
use Magento\Framework\App\Area;
use Magento\Store\Model\Store;

use \Magento\Framework\Stdlib\DateTime\DateTime;
use HoangCong\Blog\Api\Data\CommentInterface;
use Magento\Customer\Model\Session;
use HoangCong\Blog\Api\CommentRepositoryInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Mageplaza\Smtp\Mail\Rse\Mail;

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
	/**
	 * @var \HoangCong\Blog\Model\Comment $comment
	 */
	protected $comment;



    /**
     * @var Mail
     */
    protected $mailResource;

	/**
	 * @var CommentRepositoryInterface
	 */
	protected $commentRepository;

	protected $storeManager;
	protected $customerRepository;

	protected $_transportBuilder;

	protected $scopeConfig;
	protected $inlineTranslation;
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		Session $session,
		CommentInterface $comment,
		CommentRepositoryInterface $commentRepository,
		StoreManagerInterface $storeManager,
		CustomerRepositoryInterface $customerRepository,
		TransportBuilder $transportBuilder,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		 StateInterface $inlineTranslation,
		 Mail $mailResource
	) {
		$this->mailResource = $mailResource;
		$this->inlineTranslation= $inlineTranslation;
		$this->_transportBuilder= $transportBuilder;
		$this->customerRepository= $customerRepository;
		$this->scopeConfig= $scopeConfig;
		$this->storeManager = $storeManager;
		$this->_pageFactory = $pageFactory;
		$this->_session = $session;
		$this->comment = $comment;
		$this->commentRepository = $commentRepository;
		return parent::__construct($context);
	}

	public function execute()
	{

		if (!$this->_request->isAjax()) {
			return null;
		}

			$customer_id = $this->_session->getCustomerId();
			if ($customer_id == null) return null;
			
			$data = $this->_request->getPostValue();
			$post_id = (int)trim($data['id']);
			$content = $data['comment'];
			$content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
			
			if (empty(trim($content))) return null;
			$this->comment->setContentComment($content);
		$this->comment->customer_id = $customer_id;
		$this->comment->post_id = $post_id;
		
		$date = ObjectManager::getInstance()->create(DateTime::class)->gmtDate();
		$this->comment->setCreationTime($date);
		/**@var \Magento\Customer\Model\Customer $customer*/
		$customer= $this->_session->getCustomer();
		
			
		if ($this->commentRepository->save($this->comment)) {
			// var_dump($config);
			$templateOptions = array('area' => \Magento\Framework\App\Area::AREA_FRONTEND, 
			'store' =>    $this->storeManager->getStore()->getId()
			) ;

			$templateVars = array(
				'name'=> $customer->getName()
			);
			$from = $this->scopeConfig->getValue('blog/general/sender_email') ;
			
			$this->inlineTranslation->suspend();
			$emailDestination= $customer->getEmail();
			$to = $emailDestination;

			$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
			$templateId = $this->scopeConfig->getValue('blog/general/template', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			

			$transport = $this->_transportBuilder->setTemplateIdentifier($templateId,$storeScope)
			->setTemplateOptions($templateOptions)
				->setTemplateVars($templateVars)
				->setFrom($from,$storeScope)
				->addTo($to)
				->setReplyTo($to)
				->getTransport();

				$transport->sendMessage();

			$this->inlineTranslation->resume();

			echo "<b style='color:green;'> Successfully added, please wait for the admin to process</b>";
		} else echo " <b style='color:red;'>fail, An error has occurred </b>";
	}
}