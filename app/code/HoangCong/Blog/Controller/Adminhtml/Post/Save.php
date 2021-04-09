<?php
namespace HoangCong\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use HoangCong\Blog\Model\ResourceModel\Post\CollectionFactory;
use HoangCong\Blog\Api\PostRepositoryInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NotFoundException;
use Magento\Ui\Component\Form;
use \Magento\Framework\Stdlib\DateTime\DateTime;

class Save extends Action implements HttpPostActionInterface
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

    private $form;
    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \HoangCong\Blog\Model\ResourceModel\Post\CollectionFactory $collectionFactory
     * @param \HoangCong\Blog\Api\PostRepositoryInterface $postRepository
     */

     protected $date;
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        PostRepositoryInterface $postRepository,
        Form $form
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->postRepository = $postRepository;
        $this->form= $form;
        parent::__construct($context);
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

        $data=$this->getRequest()->getPostValue();
        $debug= ObjectManager::getInstance()->create(\Psr\Log\LoggerInterface::class);
        $debug->debug(json_encode($data));        
        /**@var \HoangCong\Blog\Model\Post*/

        $post= ObjectManager::getInstance()->create(\HoangCong\Blog\Model\Post::class);
        $post->setTitle($data['title']);
        $post->setContent($data['content']);
        $post->setIsActive(boolval($data['is_active']));
        $date= ObjectManager::getInstance()->create(DateTime::class)->gmtDate();
        $post->setCreationTime( $date);
        $this->postRepository->save($post);

        // $debug->debug(print_r($post->debug()));

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('blog/post');

    }
    protected function _isAllowed()
    {
            return $this->_authorization->isAllowed('HoangCong_Blog::save');
    }
}





