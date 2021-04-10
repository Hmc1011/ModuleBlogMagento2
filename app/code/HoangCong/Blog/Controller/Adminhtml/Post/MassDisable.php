<?php
namespace HoangCong\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use HoangCong\Blog\Model\ResourceModel\Post\CollectionFactory;
use HoangCong\Blog\Api\PostRepositoryInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NotFoundException;
use Magento\Ui\Component\MassAction\Filter;

class MassDisable extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level
     */
    const ADMIN_RESOURCE = 'HoangCong_Blog::save';

    /**
     * @var \HoangCong\Blog\Model\ResourceModel\Post\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \HoangCong\Blog\Api\PostRepositoryInterface
     */
    private $postRepository;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \HoangCong\Blog\Model\ResourceModel\Post\CollectionFactory $collectionFactory
     * @param \HoangCong\Blog\Api\PostRepositoryInterface $postRepository
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        PostRepositoryInterface $postRepository
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->postRepository = $postRepository;
        parent::__construct($context);
    }

    /**
     * post disable action
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        if (!$this->getRequest()->isPost()) {
            throw new NotFoundException(__('Page not found'));
        }
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $postDisabled = 0;
        foreach ($collection->getItems() as $post) {
            $this->postRepository->disable($post);
            $postDisabled++;
        }

        if ($postDisabled) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been disabled.', $postDisabled)
            );
        }
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath($this->_redirect->getRefererUrl() );
    }
    protected function _isAllowed()
    {
            return $this->_authorization->isAllowed('HoangCong_Blog::save');
    }
}
