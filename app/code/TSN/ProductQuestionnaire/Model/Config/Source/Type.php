<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Type implements ArrayInterface
{
    /** @return array */
    public function toOptionArray()
    {
        return [
            ['value' => 'text', 'label'     => __('Text')],
            ['value' => 'checkbox', 'label' => __('Checkbox')],
            ['value' => 'select', 'label'   => __('Select')]
        ];
    }
}
