<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_Base
 */

namespace TSN\Base\Block;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Backend\Block\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\View\Helper\Js;
use Magento\Framework\View\LayoutFactory;
use Magento\Config\Block\System\Config\Form\Fieldset;
use Magento\Framework\App\State;
use Magento\Config\Block\System\Config\Form\Field;

class Info extends Fieldset
{
    /** @var LayoutFactory */
    protected $_layoutFactory;

    /** @var State */
    protected $appState;

    /**
     * Info constructor.
     * @param Context $context
     * @param Session $authSession
     * @param Js $jsHelper
     * @param LayoutFactory $layoutFactory
     * @param State $appState
     * @param array $data
     */
    public function __construct(
        Context $context,
        Session $authSession,
        Js $jsHelper,
        LayoutFactory $layoutFactory,
        State $appState,
        array $data = []
    ) {
        parent::__construct($context, $authSession, $jsHelper, $data);
        $this->_layoutFactory = $layoutFactory;
        $this->appState = $appState;
    }

    /**
     * Render fieldset html
     *
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $html = $this->_getHeaderHtml($element);

        $html .= $this->_getGeneralInfo($element);
        $html .= $this->_getMagentoMode($element);

        $html .= $this->_getFooterHtml($element);

        return $html;
    }

    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     */
    protected function _getFieldRenderer()
    {
        if (empty($this->_fieldRenderer)) {
            $layout = $this->_layoutFactory->create();

            $this->_fieldRenderer = $layout->createBlock(
                Field::class
            );
        }

        return $this->_fieldRenderer;
    }

    /**
     * @return mixed
     */
    protected function _getMagentoMode($fieldset)
    {
        $label = __("Magento Mode");
        $mode = $this->appState->getMode();
        $mode = ucfirst($mode);

        $field = $fieldset->addField('magento_mode', 'label', array(
            'name'  => 'dummy',
            'label' => $label,
            'value' => $mode,
        ))->setRenderer($this->_getFieldRenderer());

        return $field->toHtml();
    }

    /**
     * @param $fieldset
     * @return mixed
     */
    protected function _getGeneralInfo($fieldset) {


            $value = '<div class="red"><a target=\'_blank\' href=\'https://tsn-media.com/en/\'>';
            $value .= __('www.tsn-media.com') . "</a></div>";


        $label = __('TSN-Media');

        $field = $fieldset->addField('tsn_info', 'label', array(
            'name'  => 'dummy',
            'label' => $label,
            'after_element_html' => $value,
        ))->setRenderer($this->_getFieldRenderer());

        return $field->toHtml();
    }
}
