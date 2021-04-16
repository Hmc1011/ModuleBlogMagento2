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
use Magento\Authorization\Model\Role;
use Magento\Authorization\Model\Rules;
use Magento\User\Api\Data\UserInterfaceFactory;
use Magento\User\Model\User;

class Index extends \Magento\Framework\App\Action\Action
{
	const RULE=['HoangCong_Blog::comment','Magento_Backend::all'];
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
		 * @var Rules $rules
		 */
	protected $rules;

	/**
	 * @var User
	 */
	protected $user;

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

	/**
	 * @var Role
	 */
	protected $role;
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
		 Mail $mailResource,
		 Role $role,
		 Rules $rules,
		 UserInterfaceFactory $userIF
	) {
		$this->user= $userIF->create();
		$this->rules=$rules;
		$this->role= $role;
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

	protected function sendMail($templateId,$templateVars,$to){
		$templateOptions = array('area' => \Magento\Framework\App\Area::AREA_FRONTEND, 
		'store' =>    $this->storeManager->getStore()->getId()
		) ;
		$from = $this->scopeConfig->getValue('blog/general/sender_email') ;
		
		$this->inlineTranslation->suspend();

		$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
		
		$transport = $this->_transportBuilder->setTemplateIdentifier($templateId,$storeScope)
		->setTemplateOptions($templateOptions)
			->setTemplateVars($templateVars)
			->setFrom($from,$storeScope)
			->addTo($to)
			->setReplyTo($to)
			->getTransport();

			$transport->sendMessage();
		$this->inlineTranslation->resume();

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
		
		
		
			
		if ($this->commentRepository->save($this->comment)) {
			// var_dump($config);

			//send mail to customer
			$customer= $this->_session->getCustomer();
			$templateVars = array(
				'name'=> $customer->getName()
			);
			$to= $customer->getEmail();
			$templateId = $this->scopeConfig->getValue('blog/general/template', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

			$this->sendMail($templateId,$templateVars,$to);


			$role_ids= $this->rules->getCollection()
			->addFieldToFilter('resource_id',self::RULE)
			->addFilter('permission','allow')
			->getColumnValues('role_id');
			// var_dump($role_ids);
			$roles= $this->role->getCollection()
			->addFieldToFilter('role_id',$role_ids);

			/**
			 * @var Role $role
			 */

			foreach ($roles as $role)
			{
				$userIds= $role->getRoleUsers() ;
				$emails=	$this->user->getCollection()
				->addFieldToFilter('main_table.user_id',['in'=>$userIds])
				->getColumnValues('email');
				//send mail to admins
				if ( !empty($emails)){

					$templateVars = [];
					$to= $emails;
					$templateId = 'blog_general_admin_new_comment';
		
					$this->sendMail($templateId,$templateVars,$to);

				}

			}

			echo "<b style='color:green;'>". __('Successfully added, please wait for the admin to process'). "</b>";
		} else echo " <b style='color:red;'>". __('fail, An error has occurred').  "</b>";
	}
}