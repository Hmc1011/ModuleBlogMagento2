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

class MassDelete extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level
     */
    const ADMIN_RESOURCE = 'HoangCong_Blog::post_delete';
    CONST CACHE_TAG="Hoangcong_Blog_List_Post";

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



    private $fullPageCache;

    private function getCache()
    {
        if (!$this->fullPageCache) {
            $this->fullPageCache = \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\PageCache\Model\Cache\Type::class
            );
        }
        return $this->fullPageCache;
    }

    public function cleanByTags($tags)
    {
        $this->getCache()->clean(\Zend_Cache::CLEANING_MODE_MATCHING_TAG, $tags);
    }


    /**
     * post delete action
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        if (!$this->getRequest()->isPost()) {
            throw new NotFoundException(__('Page not found'));
        }
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $postDeleted = 0;
        foreach ($collection->getItems() as $post) {
            $this->postRepository->delete($post);
            $postDeleted++;
        }
        
        if ($postDeleted) {
            $this->cleanByTags([self::CACHE_TAG]);

            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been deleted.', $postDeleted)
            );
        }
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('blog/post');
    }
    protected function _isAllowed()
    {
            return $this->_authorization->isAllowed('HoangCong_Blog::post_delete');
    }
}
