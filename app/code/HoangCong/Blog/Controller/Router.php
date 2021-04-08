<?php
/**
 * Copyright © HoangCong, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace HoangCong\Blog\Controller;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
// use Magento\Framework\App\Route\ConfigInterface;
// use Magento\Framework\App\Router\ActionList;
use Magento\Framework\App\RouterInterface;

class Router implements RouterInterface
{
    
    /**
     * @var ActionFactory
     */
    private $actionFactory;

    protected $logger;
    protected $_postFactory;
    protected $_urlRewriteFactory;
     

    public function __construct(
        ActionFactory $actionFactory,
        \HoangCong\Blog\Model\PostFactory $postFactory,
        \Psr\Log\LoggerInterface $Logger,
        \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory
        )     
    {
        $this->logger = $Logger;
        $this->actionFactory = $actionFactory;
        // $this->actionList = $actionList;
        // $this->routeConfig = $routeConfig;
        $this->_postFactory= $postFactory;        
        $this->_urlRewriteFactory= $urlRewriteFactory;

    }

    /**
     * @param RequestInterface $request
     * @return ActionInterface|null
     */
    public function match(RequestInterface $request)
    {
        $url_key = $request->getPathInfo() ;
        $prefix = '/blog/';
        $str = $url_key;

        if (substr($str, 0, strlen($prefix)) == $prefix) {
                $url_key = substr($str, strlen($prefix));
        } 

        \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug($request->getPathInfo());
        $url_key= rtrim($url_key,'/');
        $this->logger->debug($url_key);

        $post= $this->_postFactory->create();
        $post_id= $post->checkUrlKey($url_key);
        // $this->logger->debug( var_dump($request));

        if  (!$post_id)
        {
            return null;         }

         $request->setModuleName('blog')->setControllerName('view')
         ->setActionName('Index')->setParam('post_id',$post_id);

         $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS,$url_key);
         
         $urlRewriteModel = $this->_urlRewriteFactory->create();
         /* set current store id */
         $urlRewriteModel->setStoreId(1);
         /* this url is not created by system so set as 0 */
         $urlRewriteModel->setIsSystem(0);
         /* unique identifier - set random unique value to id path */
         // $urlRewriteModel->setIdPath(rand(1, 100000));
         /* set actual url path to target path field */
 
         $urlRewriteModel->setTargetPath("blog/". $request->getAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS) );
         /* set requested path which you want to create */
         $urlRewriteModel->setRequestPath($request->getAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS).".html" );
         /* set current store id */
          try {
              $urlRewriteModel->save();
 
          }
           catch (\Exception $e) 
           {      
             \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)
             ->debug($e->getMessage());            
               }

         return $this->actionFactory->create('Magento\Framework\App\Action\Forward');



        //  return $this->actionFactory->create(\Magento\Framework\App\Action\Forward::class, ['request' => $request]);
    }
}