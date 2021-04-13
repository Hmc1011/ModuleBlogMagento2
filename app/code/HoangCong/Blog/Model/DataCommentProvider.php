<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace HoangCong\Blog\Model;

use Magento\Framework\Api\Filter;
use HoangCong\Blog\Api\CommentRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\Reporting;
/**
 * DataProvider for hoangcong_blog ui.
 */
class DataCommentProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * @var AddFilterInterface[]
     */
    private $additionalFilterPool;

    /**
     * @var CommentRepository $commentRepository
     */
    protected $commentRepository;
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param Reporting $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param array $meta
     * @param array $data
     * @param array $additionalFilterPool
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Reporting $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        array $meta = [],
        array $data = [],
        array $additionalFilterPool = [],
        CommentRepositoryInterface $commentRepository
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
        $this->commentRepository= $commentRepository;
        $this->additionalFilterPool = $additionalFilterPool;
    }

    public function getData()
    {
        $data= $this->commentRepository->getAllComment();
        for ($i=0; $i< count($data); $i++ )
        {
            $comment= $data[$i];
            $data[$i]['customer']= $comment['firstname']. $comment['middlename']. $comment['lastname'];
            $data[$i]['post']=$data[$i]['title'];
        }
        // var_dump($data);
        // exit();
        return [ 'items'=> $data,
            'totalRecords' => count($data)  ];

    }

    /**
     * @inheritdoc
     */
    public function addFilter(Filter $filter)
    {
        if (!empty($this->additionalFilterPool[$filter->getField()])) {
            $this->additionalFilterPool[$filter->getField()]->addFilter($this->searchCriteriaBuilder, $filter);
        } else {
            parent::addFilter($filter);
        }
    }
}
