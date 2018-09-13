<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Block\Product;

use Magento\Framework\View\Element\Template;

class ConfigurableOption extends Template
{

    /**
     * @return mixed
     */
    public function getColorLabel()
    {
        return $this->_request->getParam('colorLabel');
    }

    /**
     * @return mixed
     */
    public function getSizeLabel()
    {
        return $this->_request->getParam('sizeLabel');
    }
}