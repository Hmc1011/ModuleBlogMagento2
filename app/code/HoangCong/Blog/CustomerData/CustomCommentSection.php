<?php
namespace HoangCong\Blog\CustomerData;

use HoangCong\Blog\Api\CommentRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Url;

class CustomCommentSection extends DataObject implements SectionSourceInterface
{

    /** @var \Magento\Framework\App\RequestInterface*/
    protected $_request;

    protected $commentRepository;

    protected $session;
    public function __construct(\Magento\Framework\App\RequestInterface $request,
    CommentRepositoryInterface $commentRepository,
    Session $session
    )
    {
        $this->session= $session;
        $this->_request= $request;
        $this->commentRepository= $commentRepository;
    }

    public function getSectionData()
    {
        $customerID= $this->session->getCustomerId();
        if  ($customerID==null)  return ['comments'=>''];
    
        /**@var Url $url */
        $postID=0;
        $comments= $this->commentRepository->getNewComments($customerID,$postID);
        $data = ['comments' => $comments];
        return $data;
    }
}