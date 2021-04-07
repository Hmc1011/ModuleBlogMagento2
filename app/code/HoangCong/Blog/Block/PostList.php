<?php
namespace HoangCong\Blog\Block;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use HoangCong\Blog\Api\PostRepositoryInterface;

use HoangCong\Blog\Api\Data\PostInterface;

class PostList extends \Magento\Framework\View\Element\Template
{
   /**
     * @var PostRepositoryInterface
     */
    protected $postRepository;
 
    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;
 
    /**
     * @var FilterGroupBuilder
     */
    protected $filterGroupBuilder;
 
    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;


	public function __construct(\Magento\Framework\View\Element\Template\Context $context,
	PostRepositoryInterface $postRepository,
	SearchCriteriaBuilder $searchCriteriaBuilder,
	FilterGroupBuilder $filterGroupBuilder,
	FilterBuilder $filterBuilder,
    array $data=[] )
	{
		parent::__construct($context,$data);
		$this->postRepository = $postRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->filterBuilder = $filterBuilder;
	}

	public function getPosts()
	{
	// 	$filter1 = $this->filterBuilder->setField('*')
	// 	->create();

	// $filter2 = $this->filterBuilder->setField('member')
	// 	->setValue('')
	// 	->setConditionType('eq')
	// 	->create();

	// $filterOr = $this->filterGroupBuilder
	// 	->addFilter($filter1)
	// 	->create();
	// $this->searchCriteriaBuilder->setFilterGroups([$filterOr]);
	$posts = $this->postRepository->getList($this->searchCriteriaBuilder->create())->getItems();
	return  $posts;
	}
}
