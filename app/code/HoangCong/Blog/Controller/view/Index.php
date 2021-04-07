<?php
declare(strict_types=1);

namespace HoangCong\Blog\Controller\view;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 */
class Index implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
      * @var RequestInterface
      */
    private $request;

    /**
     * @param PageFactory $pageFactory
     * @param RequestInterface $request
     */

    protected $_urlRewriteFactory;

    public function __construct(PageFactory $pageFactory,
     RequestInterface $request,
     \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory		
     )
    {
        $this->_urlRewriteFactory= $urlRewriteFactory;
        $this->pageFactory = $pageFactory;
        $this->request = $request;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $urlRewriteModel = $this->_urlRewriteFactory->create();
        /* set current store id */
        $urlRewriteModel->setStoreId(1);
        /* this url is not created by system so set as 0 */
        $urlRewriteModel->setIsSystem(0);
        /* unique identifier - set random unique value to id path */
        // $urlRewriteModel->setIdPath(rand(1, 100000));
        /* set actual url path to target path field */
        $urlRewriteModel->setTargetPath("blog/". $this->request->getParam('post_id') );
        /* set requested path which you want to create */
        $urlRewriteModel->setRequestPath("blog/". $this->request->getParam('post_id').".html" );
        /* set current store id */
         try {
             $urlRewriteModel->save();

         }
          catch (\Exception $e) 
          {      
              }
        
        return $this->pageFactory->create();
    }
}
