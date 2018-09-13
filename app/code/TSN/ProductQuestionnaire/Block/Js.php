<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Data\Form\FormKey;

use TSN\ProductQuestionnaire\Helper\Data;
use TSN\ProductQuestionnaire\Helper\Style;


class Js extends Template
{
    /** @var string */
    protected $_template = 'js/main.phtml';

    /** @var FormKey */
    protected $formKey;

    /** @var Data */
    protected $questionnaireHelper;

    /** @var Style */
    protected $styleHelper;

    /**
     * Js constructor.
     * @param Context $context
     * @param FormKey $formKey
     * @param Data $questionnaireHelper
     * @param Style $styleHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        FormKey $formKey,
        Data $questionnaireHelper,
        Style $styleHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->formKey             = $formKey;
        $this->questionnaireHelper = $questionnaireHelper;
        $this->styleHelper         = $styleHelper;
    }

    /** @return string */
    public function getAjaxCartInitOptions()
    {
        return $this->questionnaireHelper->getAjaxCartInitOptions();
    }

    /** @return string */
    public function getAjaxSidebarInitOptions()
    {
        $icon = $this->getViewFileUrl('images/loader.gif');
        return $this->questionnaireHelper->getAjaxSidebarInitOptions($icon);
    }

    public function getAjaxStyleInitOptions()
    {
        return $this->styleHelper->getAjaxStyleInitOptions();
    }

    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

}