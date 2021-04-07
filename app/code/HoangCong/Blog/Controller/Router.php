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

    public function __construct(
        ActionFactory $actionFactory,
        \HoangCong\Blog\Model\PostFactory $postFactory,
        \Psr\Log\LoggerInterface $Logger
        )     
    {
        $this->logger = $Logger;
        $this->actionFactory = $actionFactory;
        // $this->actionList = $actionList;
        // $this->routeConfig = $routeConfig;
        $this->_postFactory= $postFactory;        
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
         return $this->actionFactory->create('Magento\Framework\App\Action\Forward');
        //  return $this->actionFactory->create(\Magento\Framework\App\Action\Forward::class, ['request' => $request]);
    }
}