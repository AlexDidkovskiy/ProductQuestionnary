<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

use TSN\ProductQuestionnaire\Helper\Data;

class Message extends Template
{
    /** @var Data */
    protected $questionnaireHelper;

    /**
     * Message constructor.
     * @param Context $context
     * @param Data $questionnaireHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $questionnaireHelper,
        array $data
    ) {
        parent::__construct($context, $data);
        $this->questionnaireHelper = $questionnaireHelper;
    }

    /** @return mixed|string */
    public function getMessage()
    {
        $message = $this->questionnaireHelper->getScopeConfig('tsn_questionnaire/general/message');
        if (!$message) {
            $message = 'You have recently added this product to your Cart';
        }
        return $message;
    }
}