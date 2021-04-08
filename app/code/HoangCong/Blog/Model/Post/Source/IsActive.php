<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace HoangCong\Blog\Model\Post\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class IsActive implements OptionSourceInterface
{
    /**
     * @var \HoangCong\Blog\Model\Post
     */
    protected $post;

    /**
     * Constructor
     *
     * @param \HoangCong\Blog\Model\Post $post
     */
    public function __construct(\HoangCong\Blog\Model\Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->post->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
