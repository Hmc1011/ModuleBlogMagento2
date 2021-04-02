<?php
 
namespace HoangCong\Blog\Api\Data;
 
use Magento\Framework\Api\SearchResultsInterface;

interface PostSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \HoangCong\Blog\Api\Data\PostInterface[]
     */
    public function getItems();
 
    /**
     * @param \HoangCong\Blog\Api\Data\PostInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}