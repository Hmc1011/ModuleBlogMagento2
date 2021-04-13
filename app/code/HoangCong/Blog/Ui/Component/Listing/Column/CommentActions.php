<?php
namespace HoangCong\Blog\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\Url;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Backend\Model\UrlInterface;

class CommentActions extends Column
{
    /**
     * @var Url
     */
    protected $_urlBuilder;

    /**
     * @var string
     */
    protected $_viewUrl;


    /**
     * @var string
     */
    protected $_editUrl;

    /**
     * @var Magento\Backend\Model\UrlInterface
     */
    protected $_backendUrl;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\Url $urlBuilder
     * @param string $viewUrl
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Url $urlBuilder,
        $viewUrl = '',
        $editUrl='blog/post/edit', 
        array $components = [],
        array $data = [],
        UrlInterface $backendUrl

    ) {
        $this->_backendUrl= $backendUrl;
        $this->_urlBuilder = $urlBuilder;
        $this->_viewUrl    = $viewUrl;
        $this->_editUrl    = $editUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
    if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');

                if (isset($item['comment_id'])) {
                    $comment= substr($item['comment'],0,20);

                    $item[$name] = [
                        'delete'=>[
                            'href'=> $this->_backendUrl->getUrl('blog/comment/delete', ['id'=>$item['comment_id']]) ,
                            'label'=>__('Delete'),
                            'confirm'=>[
                                'title'=> __("Delete ${item['comment_id']} "),
                                'message'=> __("Are you sure want to delete a \" ${comment} \" record? ")
                            ]
                        ]

                    ];
                }
            }
        }
        return $dataSource;
    }
}
