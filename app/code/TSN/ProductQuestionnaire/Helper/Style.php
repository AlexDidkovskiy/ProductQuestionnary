<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Helper;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\AbstractHelper;

class Style extends AbstractHelper
{
    const IS_ENABLED                = 'tsn_questionnaire/general/enabled';
    const ISTTL                     = 'tsn_questionnaire/auto_close_popup/enabled_popupttl';
    const TTL                       = 'tsn_questionnaire/auto_close_popup/popupttl';
    const THEME                     = 'tsn_questionnaire/effects/theme';
    const THEME_CUSTOM_BACKGROUND   = 'tsn_questionnaire/color_design/color_background';
    const THEME_CUSTOM_TITLE        = 'tsn_questionnaire/color_design/color_title';
    const ANIMATION                 = 'tsn_questionnaire/effects/animation';
    const HEADER_BACKGROUND_COLOR   = 'tsn_questionnaire/color_design/header_background_color';
    const HEADER_TEXT_COLOR         = 'tsn_questionnaire/color_design/header_text_color';
    const BUTTON_TEXT_COLOR         = 'tsn_questionnaire/color_design/button_text_color';
    const BUTTON_BACKGROUND_COLOR   = 'tsn_questionnaire/color_design/button_background_color';

    protected $storeId;

    /** @var CustomerSession */
    protected $customerSession;

    /** @var EncoderInterface */
    protected $jsonEncoder;

    /** @var LayoutFactory */
    protected $layoutFactory;

    /**
     * Style constructor.
     * @param Context $context
     * @param CustomerSession $customerSession
     * @param LayoutFactory $layoutFactory
     * @param EncoderInterface $jsonEncoder
     */
    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        LayoutFactory $layoutFactory,
        EncoderInterface $jsonEncoder
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->layoutFactory   = $layoutFactory;
        $this->jsonEncoder     = $jsonEncoder;
    }

    /**
     * @param $store
     * @return $this
     */
    public function setStoreId($store)
    {
        $this->storeId = $store;
        return $this;
    }

    /**
     * Style option
     *
     * @return string
     */
    public function getAjaxStyleInitOptions()
    {
        $options = [
            'ajaxStyle' => [
                'popupTTL'              => $this->getTTLAjaxStyle(),
                'animation'             => $this->getAnimationAjaxStyle(),
                'backgroundColor'       => $this->getScopeConfig(self::THEME_CUSTOM_BACKGROUND),
                'headerBackgroundColor' => $this->getScopeConfig(self::HEADER_BACKGROUND_COLOR),
                'headerTextColor'       => $this->getScopeConfig(self::HEADER_TEXT_COLOR),
                'buttonTextColor'       => $this->getScopeConfig(self::BUTTON_TEXT_COLOR),
                'buttonBackgroundColor' => $this->getScopeConfig(self::BUTTON_BACKGROUND_COLOR),
            ]
        ];

        return $this->jsonEncoder->encode($options);
    }


    /**
     * TTL Count
     *
     * @return int|null
     */
    public function getTTLAjaxStyle()
    {
        if ($this->isEnabledTTLAjaxStyle()) {
            return (int)$this->scopeConfig->getValue(
                self::TTL,
                ScopeInterface::SCOPE_STORE,
                $this->storeId
            );
        } else {
            return null;
        }
    }

    /**
     * Enable TTL Count
     *
     * @return bool
     */
    public function isEnabledTTLAjaxStyle()
    {
        return (bool)$this->scopeConfig->getValue(
            self::ISTTL,
            ScopeInterface::SCOPE_STORE,
            $this->storeId
        );
    }

    /**
     * Option of popup animation style
     *
     * @return mixed
     */
    public function getAnimationAjaxStyle()
    {
        return $this->scopeConfig->getValue(
            self::ANIMATION,
            ScopeInterface::SCOPE_STORE,
            $this->storeId
        );
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getScopeConfig($path)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $this->storeId);
    }

    /**
     * Customer LoggedIn
     *
     * @return bool
     */
    public function getLoggedCustomer()
    {
        return (bool)$this->customerSession->isLoggedIn();
    }
}
