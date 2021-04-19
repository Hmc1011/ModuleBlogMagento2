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
    CONST CACHE_TAG="Hoangcong_Blog_List_Post";

    /**
     * @var \HoangCong\Blog\Model\ResourceModel\Post\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \HoangCong\Blog\Api\PostRepositoryInterface
     */
    private $postRepository;
    protected $date;
    protected $_urlRewriteFactory;
    private $form;
    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \HoangCong\Blog\Model\ResourceModel\Post\CollectionFactory $collectionFactory
     * @param \HoangCong\Blog\Api\PostRepositoryInterface $postRepository
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        PostRepositoryInterface $postRepository,
        Form $form,
        \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->postRepository = $postRepository;
        $this->form = $form;
        $this->_urlRewriteFactory = $urlRewriteFactory;
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
        $data = $this->getRequest()->getPostValue();
        $debug = ObjectManager::getInstance()->create(\Psr\Log\LoggerInterface::class);
        $debug->debug(json_encode($data));
        /**@var \HoangCong\Blog\Model\Post*/

        $post = ObjectManager::getInstance()->create(\HoangCong\Blog\Model\Post::class);
        $date = ObjectManager::getInstance()->create(DateTime::class)->gmtDate();
        if (!empty(trim($data['post_id']))) {
            $post->setId($data['post_id']);
            $post->setUpdateTime($date);
        } else {
            $post->setCreationTime($date);
        }
        $post->setTitle($data['title']);
        $post->setContent($data['post']);
        $post->setIsActive(boolval($data['is_active']));
        // $debug->debug(print_r($post->debug()));

        $urlRewriteModel = $this->_urlRewriteFactory->create();
        /* set current store id */
        $urlRewriteModel->setStoreId(1);
        /* this url is not created by system so set as 0 */
        $urlRewriteModel->setIsSystem(0);
        /* unique identifier - set random unique value to id path */
        // $urlRewriteModel->setIdPath(rand(1, 100000));
        /* set actual url path to target path field */
        $slug = \Transliterator::createFromRules(
            ':: Any-Latin;'
                . ':: NFD;'
                . ':: [:Nonspacing Mark:] Remove;'
                . ':: NFC;'
                . ':: [:Punctuation:] Remove;'
                . ':: Lower();'
                . '[:Separator:] > \'-\''
        )->transliterate($data['title']);

        $data['url_key'] = empty(trim($data['url_key'])) ? $slug : $data['url_key'] ;

        $urlRewriteModel->setTargetPath('blog/' . $data['url_key']);
        /* set requested path which you want to create */
        $urlRewriteModel->setRequestPath($data['url_key'].".html");
        /* */
        try {
            $urlRewriteModel->save();
        } catch (\Exception $e) {
            \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)
                ->debug($e->getMessage());
        }

        $post->setUrlKey($data['url_key']);
        $this->postRepository->save($post);
        $this->messageManager->addSuccessMessage(
            __('you have successfully saved the post ')
        );


        $this->cleanByTags([self::CACHE_TAG]);
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath($this->_redirect->getRefererUrl() );

    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('HoangCong_Blog::save');
    }
}
