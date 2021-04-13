<?php
 
namespace HoangCong\Blog\Model;
 
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use HoangCong\Blog\Api\Data\CommentInterface;
use HoangCong\Blog\Api\Data\CommentSearchResultInterface;
use HoangCong\Blog\Api\Data\CommentSearchResultInterfaceFactory;
use HoangCong\Blog\Api\CommentRepositoryInterface;
use HoangCong\Blog\Model\ResourceModel\Comment\CollectionFactory as CommentCollectionFactory;
use HoangCong\Blog\Model\ResourceModel\Comment\Collection;
 
class CommentRepository implements CommentRepositoryInterface
{
    /**
     * @var CommentFactory
     */
    private $commentFactory;
 
    /**
     * @var CommentCollectionFactory
     */
    private $commentCollectionFactory;
 
    public function __construct(
        CommentFactory $commentFactory,
        CommentCollectionFactory $commentCollectionFactory
    ) {
        $this->commentFactory = $commentFactory;
        $this->commentCollectionFactory = $commentCollectionFactory;
    }

    public function getAllCommentOfPost($post_id)
    {
        /**
         * @var Comment $comment
         */
       $comment= $this->commentFactory->create();
       return $comment->getAllCommentOfPost($post_id);

    }

    
    public function save($comment)
    {
        /**@var \HoangCong\Blog\Model\Comment $comment */
        try{
            return  $comment->save();       
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

}