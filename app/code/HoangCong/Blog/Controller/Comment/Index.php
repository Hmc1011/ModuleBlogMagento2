<?php
namespace HoangCong\Blog\Controller\Comment;

use HoangCong\Blog\Api\Data\CommentInterface;
use Magento\Customer\Model\Session;
use HoangCong\Blog\Api\CommentRepositoryInterface;

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
 * @var CommentRepositoryInterface
 */
protected $commentRepository;


	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		Session $session,
		CommentInterface $comment,
		CommentRepositoryInterface $commentRepository		
		)
	{
		$this->_pageFactory = $pageFactory;
		$this->_session= $session;
		$this->comment= $comment;
		$this->commentRepository= $commentRepository;
		return parent::__construct($context);
	
	}

	public function execute()
	{
        if (!$this->_request->isAjax()) {
            return null;
        }
		
		$customer_id= $this->_session->getCustomerId();
		if ($customer_id==null) return null;
		
		$data= $this->_request->getPostValue();
		$post_id= $data['id'];
		$content= $data['comment'];
		$content= htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
		
		// if (empty(trim($content))) return null;
		$this->comment->setContentComment($content);
		$this->comment->customer_id= $customer_id;
		$this->comment->post_id = $post_id;

		if  ($this->commentRepository->save($this->comment))
		echo "<b style='color:green;'> Successfully added, please wait for the admin to process</b>";
		else echo " <b style='color:red;'>fail, An error has occurred </b>";
	}
}