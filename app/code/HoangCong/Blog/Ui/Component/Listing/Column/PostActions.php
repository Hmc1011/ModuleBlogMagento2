<?php
namespace HoangCong\Blog\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\Url;
use Magento\Ui\Component\Listing\Columns\Column;

class PostActions extends Column
{
    /**
     * @var UrlInterface
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
        $editUrl='edit', 
        array $components = [],
        array $data = []
    ) {
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
                if (isset($item['post_id'])) {
                    $item[$name] = [
                        'view on frontend'=>[
                        'href'  => $this->_urlBuilder->getUrl($this->_viewUrl, ['blog' => $item['url_key']]),
                        'target' => '_blank',
                        'label' => __('View on Frontend')],
                        'edit'=>[
                            'href'=> $this->_urlBuilder->getUrl($this->_editUrl, ['id' => $item['post_id']]) ,
                            'label'=>__('Edit')
                        ],
                        'delete'=>[
                            'href'=> $this->_urlBuilder->getUrl('delete', ['id' => $item['post_id']]) ,
                            'label'=>__('Delete'),
                            'confirm'=>[
                                'title'=> __('Delete "${ $data.title }" '),
                                'message'=> __('Are you sure want to delete a "${ $.$data.title }" record?  ')
                            ]
                        ]

                    ];
                }
            }
        }
        return $dataSource;
    }
}
