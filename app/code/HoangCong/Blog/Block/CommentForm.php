<?php
namespace HoangCong\Blog\Block;
use HoangCong\Blog\Api\CommentRepositoryInterface;
use Magento\Framework\UrlInterface;
use Magento\Customer\Model\Session;

class CommentForm extends \Magento\Framework\View\Element\Template
{

    protected $_commentRepository;

        /**
         * @var UrlInterface
         */
    protected $_url;
    protected $session;

	public function __construct(\Magento\Framework\View\Element\Template\Context $context,
    CommentRepositoryInterface $commentRepository,
    UrlInterface $url,
    Session $session,
    array $data=[] )
	{
        $this->session= $session;
		parent::__construct($context,$data);
        $this->_commentRepository= $commentRepository;
        $this->_url= $url;
	}
    public function customerLoged()
    {
            var_dump($this->session->isLoggedIn());
            return $this->session->isLoggedIn();
        $om = \Magento\Framework\App\ObjectManager::getInstance();
        /** @var \Magento\Framework\App\Http\Context $context */
        $context = $om->get('Magento\Framework\App\Http\Context');
        /** @var bool $isLoggedIn */
        $isLoggedIn = $context->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
        
            return ($isLoggedIn == 1);
    }

    public function getUrlAjax()
    {
            return $this->_url->getUrl('blog/comment');
    }
    public function getPostId()
    {
        $post_id= $this->_request->getParam('post_id');
        // echo $post_id;
        return $post_id;
    }
}
