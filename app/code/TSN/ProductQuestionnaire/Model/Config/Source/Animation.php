<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Animation implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'fade', 'label'         => __('Fade In')],
            ['value' => 'slide_top', 'label'    => __('Slide from Top')],
            ['value' => 'slide_bottom', 'label' => __('Slide from Bottom')],
            ['value' => 'slide_left', 'label'   => __('Slide from Left')],
            ['value' => 'slide_right', 'label'  => __('Slide from Right')]
        ];
    }
}
