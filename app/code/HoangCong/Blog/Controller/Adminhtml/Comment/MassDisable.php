<?php
namespace HoangCong\Blog\Controller\Adminhtml\Comment;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use HoangCong\Blog\Model\ResourceModel\Comment\CollectionFactory;
use HoangCong\Blog\Api\CommentRepositoryInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NotFoundException;
use Magento\Ui\Component\MassAction\Filter;

class MassDisable extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level
     */
    const ADMIN_RESOURCE = 'HoangCong_Blog::comment';

    /**
     * @var \HoangCong\Blog\Model\ResourceModel\Comment\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \HoangCong\Blog\Api\CommentRepositoryInterface
     */
    private $commentRepository;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \HoangCong\Blog\Model\ResourceModel\Comment\CollectionFactory $collectionFactory
     * @param \HoangCong\Blog\Api\CommentRepositoryInterface $commentRepository
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        CommentRepositoryInterface $commentRepository
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->commentRepository = $commentRepository;
        parent::__construct($context);
    }

    /**
     * comment disable action
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        if (!$this->getRequest()->isPost()) {
            throw new NotFoundException(__('Page not found'));
        }
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $commentDisabled = 0;
        foreach ($collection->getItems() as $comment) {
            $this->commentRepository->disable($comment);
            $commentDisabled++;
        }

        if ($commentDisabled) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been disabled.', $commentDisabled)
            );
        }
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath($this->_redirect->getRefererUrl() );
    }
    protected function _isAllowed()
    {
            return $this->_authorization->isAllowed('HoangCong_Blog::comment');
    }
}
