<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Block\Adminhtml\Order\View\Tab;

class Info extends \Magento\Sales\Block\Adminhtml\Order\View\Tab\Info
{
    /** @return string */
    public function getQuestionHtml()
    {
        return $this->getChildHtml('order_product_question');
    }

}
