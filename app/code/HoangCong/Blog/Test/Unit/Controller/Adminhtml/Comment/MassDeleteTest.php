<?php
declare(strict_types=1);

namespace HoangCong\Blog\Test\Unit\Controller\Adminhtml\Comment;

use Magento\Backend\Model\Auth\StorageInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use HoangCong\Blog\Model\ResourceModel\Comment\CollectionFactory;
use HoangCong\Blog\Api\CommentRepositoryInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NotFoundException;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use HoangCong\Blog\Model\ResourceModel\Comment\Collection;
use Magento\Framework\App\ResponseInterface;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Backend\Model\Auth;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use HoangCong\Blog\Controller\Adminhtml\Comment\MassDelete;


class MassDeleteTest extends TestCase
{

    /**
     * @var \Hoangcong\Blog\Controller\Adminhtml\Comment\MassDelete $unit
     */
    protected $unit;

        /** @var RequestInterface|MockObject */
        protected $request;

    /**
     * @var Filter|MockObject $filter
     */
    /**
     * @var MockObject
     */
     protected $messageManager;
     protected $filter;
    /**
     * @var ObjectManager|MockObject $objectManager
     */
    protected $objectManager;

    protected $resultFactory;
    /**
     * @var CommentRepositoryInterface|MockObject $commentRepository
     */
    protected $commentRepository;

    /**
     * @var MockObject $collectionFactory
     */
    protected $collectionFactory;

    protected function setUp():void
    {
        $context = $this->createMock(Context::class);
        $resultRedirectFactory = $this->createPartialMock(
            RedirectFactory::class,
            ['create']
        );
        $this->request = $this->getMockForAbstractClass(
            RequestInterface::class,
            [],
            '',
            false,
            true,
            true,
            ['getParam', 'getPost','isPost']
        );
        // $this->request= $this->getMockBuilder(RequestInterface::class)
        //     ->addMethods(['isPost'])
        //     ->getMockForAbstractClass();

        // \Magento\Framework\App\ObjectManager::getInstance()->create(\Psr\Log\LoggerInterface)
        // ->debug( var_dump() )

        $auth = $this->createPartialMock(Auth::class, ['getAuthStorage']);
        $this->authStorage = $this->getMockBuilder(StorageInterface::class)
            ->addMethods(['setDeletedPath'])
            ->onlyMethods(['processLogin', 'processLogout', 'isLoggedIn', 'prolong'])
            ->getMockForAbstractClass();
        $eventManager = $this->getMockForAbstractClass(
            ManagerInterface::class,
            [],
            '',
            false,
            true,
            true,
            ['dispatch']
        );
        $response = $this->getMockForAbstractClass(
            ResponseInterface::class,
            [],
            '',
            false
        );
        $this->messageManager = $this->getMockForAbstractClass(
            \Magento\Framework\Message\ManagerInterface::class,
            [],
            '',
            false,
            true,
            true,
            ['addSuccessMessage']
        );

        $this->resultFactory =$this->createMock(\Magento\Framework\Controller\ResultFactory::class);
        $resultInterface = $this->getMockForAbstractClass(\Magento\Framework\Controller\ResultInterface::class,[],'',false,false,true,['setPath']);
        $result= $this->createMock(\Magento\Backend\Model\View\Result\Redirect::class);

        $resultInterface->expects($this->any())->method('setPath')->willReturn($result);        
        $this->resultFactory->expects($this->any())->method('create')->willReturn($resultInterface);


        $context->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->request);
        $context->expects($this->any())
            ->method('getResponse')
            ->willReturn($response);
        $context->expects($this->any())
            ->method('getMessageManager')
            ->willReturn($this->messageManager);
        $context->expects($this->any())
            ->method('getEventManager')
            ->willReturn($eventManager);
        $context->expects($this->any())
            ->method('getAuth')
            ->willReturn($auth);
        $context->expects($this->once())->method('getResultFactory')->willReturn($this->resultFactory);
        $auth->expects($this->any())
            ->method('getAuthStorage')
            ->willReturn($this->authStorage);
            

       
        // $this->resultFactory->expects($this->any())->method('create')->willReturn($this->resultRedirect);
        
        $this->collectionFactory= $this->createMock(CollectionFactory::class);   
        $this->filter= $this->createMock(Filter::class);

        $this->commentRepository= $this->getMockForAbstractClass(CommentRepositoryInterface::class,[],'',false,false,true,['delete']);

        $this->unit = (new ObjectManagerHelper($this))->getObject(
            MassDelete::class,
            [
                'context' => $context,
                'filter'=>$this->filter,
                'collectionFactory'=>$this->collectionFactory,
                'commentRepository'=>$this->commentRepository
            ]
        );
    }

    public function testWithNotPostRequest(){

        $this->request->method('isPost')->willReturn(false);
        $this->expectException(NotFoundException::class);
        $this->unit->execute();

    }
    public function testWithFilter(){
        $this->request->method('isPost')->willReturn(true);

        $commentInterface= $this->createMock(\HoangCong\Blog\Api\Data\CommentInterface::class);
        $comments=[$commentInterface];

        $collection= $this->createPartialMock(Collection::class,['getItems']);
        $collection->expects($this->once())->method('getItems')->willReturn($comments);

        $abstractDb= $this->createMock(\Magento\Framework\Data\Collection\AbstractDb::class);

        $this->collectionFactory->method('create')->willReturn($abstractDb);
        $this->commentRepository->method('delete')->with($commentInterface)->willReturn(null);

        $this->filter->expects($this->once())->method('getCollection')->with($abstractDb)->willReturn($collection);

        $this->messageManager->expects($this->once())->method('addSuccessMessage')->with(
            __('A total of %1 record(s) have been deleted.', 1)
        );
        $this->unit->execute();

    }

}