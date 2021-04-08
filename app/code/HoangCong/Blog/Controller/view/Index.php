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
      
        return $this->pageFactory->create();
    }
}
