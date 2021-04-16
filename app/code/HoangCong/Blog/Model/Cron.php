<?php
namespace HoangCong\Blog\Model;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Authorization\Model\Role;
use Magento\Authorization\Model\Rules;
use Magento\User\Api\Data\UserInterfaceFactory;
use Magento\User\Model\User;


class Cron {
    const RULE=['HoangCong_Blog::comment','Magento_Backend::all'];

/** @var \Magento\Framework\ObjectManagerInterface */
protected $objectManager;

    /**
     * @var Rules $rules
     */
protected $rules;

/**
 * @var User
 */
protected $user;

protected $storeManager;

protected $_transportBuilder;

protected $scopeConfig;
protected $inlineTranslation;



public function __construct(
\Magento\Framework\ObjectManagerInterface $objectManager,
StoreManagerInterface $storeManager,
TransportBuilder $transportBuilder,
\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
 StateInterface $inlineTranslation,
 Role $role,
 Rules $rules,
 UserInterfaceFactory $userIF

) {
$this->scopeConfig= $scopeConfig;
$this->objectManager = $objectManager;
$this->storeManager= $storeManager;
$this->inlineTranslation= $inlineTranslation;
$this->_transportBuilder= $transportBuilder;
$this->rules= $rules;
$this->role= $role;
$this->user= $userIF->create();
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


public function sendMailToAdminWhenAppearNewComment() {

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
					$templateId = 'comment_notification_email_admin_cron';
		
					$this->sendMail($templateId,$templateVars,$to);

				}

}
}
}