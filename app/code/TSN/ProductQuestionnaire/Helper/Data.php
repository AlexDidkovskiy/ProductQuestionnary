<?php


namespace TSN\ProductQuestionnaire\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Json\DecoderInterface;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Catalog\Helper\Image;

use TSN\ProductQuestionnaire\Helper\Style as SuiteHelper;


class Data extends AbstractHelper
{
    const IS_ENABLED   = 'tsn_questionnaire/general/enabled';

    /**
     * Currently selected store ID if applicable
     *
     * @var int
     */
    protected $storeId;

    /** @var LayoutFactory */
    protected $layoutFactory;

    /** @var EncoderInterface */
    protected $jsonEncoder;

    /** @var DecoderInterface */
    protected $jsonDecoder;

    /** @var PricingHelper */
    protected $pricingHelper;

    /** @var Image */
    protected $prdImageHelper;

    /** @var SuiteHelper */
    protected $suiteHelper;

    /**
     * Data constructor.
     * @param Context $context
     * @param LayoutFactory $layoutFactory
     * @param EncoderInterface $jsonEncoder
     * @param DecoderInterface $jsonDecoder
     * @param PricingHelper $pricingHelper
     * @param Image $imageHelper
     * @param SuiteHelper $suiteHelper
     */
    public function __construct(
        Context $context,
        LayoutFactory $layoutFactory,
        EncoderInterface $jsonEncoder,
        DecoderInterface $jsonDecoder,
        PricingHelper $pricingHelper,
        Image $imageHelper,
        SuiteHelper $suiteHelper
    ) {
        $this->layoutFactory    = $layoutFactory;
        $this->jsonEncoder      = $jsonEncoder;
        $this->jsonDecoder      = $jsonDecoder;
        $this->pricingHelper    = $pricingHelper;
        $this->prdImageHelper   = $imageHelper;
        $this->suiteHelper      = $suiteHelper;
        parent::__construct($context);
    }

    /**
     * Set a specified store ID value
     *
     * @param $store
     * @return $this
     */
    public function setStoreId($store)
    {
        $this->storeId = $store;
        return $this;
    }

    /**
     * @param $product
     * @return string
     */
    public function getOptionsPopupHtml($product)
    {
        $layout = $this->layoutFactory->create(['cacheable' => false]);
        $layout->getUpdate()->addHandle('questionnaire_options_popup')->load();
        $layout->generateXml();
        $layout->generateElements();
        $result = $layout->getOutput();
        $layout->__destruct();
        return $result;
    }

    /**
     * @param $product
     * @return string
     */
    public function getQuestionnaireFormHtml($product)
    {
        $layout = $this->layoutFactory->create(['cacheable' => false]);
        $layout->getUpdate()->addHandle('questionnaire_questionnaire_form')->load();
        $layout->generateXml();
        $layout->generateElements();
        $result = $layout->getOutput();
        $layout->__destruct();
        return $result;
    }

    /**
     * @param $product
     * @return string
     */
    public function getSuccessHtml($product)
    {
        $layout = $this->layoutFactory->create(['cacheable' => false]);
        $layout->getUpdate()->addHandle('questionnaire_success_message')->load();
        $layout->generateXml();
        $layout->generateElements();
        $result = $layout->getOutput();
        $layout->__destruct();
        return $result;
    }

    /**
     * @param $product
     * @return string
     */
    public function getErrorHtml($product)
    {
        $layout = $this->layoutFactory->create(['cacheable' => false]);
        $layout->getUpdate()->addHandle('questionnaire_error_message')->load();
        $layout->generateXml();
        $layout->generateElements();
        $result = $layout->getOutput();
        $layout->__destruct();
        return $result;
    }

    /**
     * @return bool
     */
    public function isEnabledAjaxcart()
    {
        return (bool)$this->scopeConfig->getValue(
            self::IS_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $this->storeId
        );
    }

    /**
     * @return string
     */
    public function getAjaxCartInitOptions()
    {
        $optionsAjaxstyle = $this->jsonDecoder->decode($this->suiteHelper->getAjaxStyleInitOptions());
        $options = [
            'questionPopup' => [
                'addToCartUrl'            => $this->_getUrl('questionnaire/cart/showPopup'),
                'addToCartInWishlistUrl'  => $this->_getUrl('questionnaire/wishlist/showPopup'),
                'checkoutCartUrl'         => $this->_getUrl('checkout/cart/add'),
                'wishlistAddToCartUrl'    => $this->_getUrl('wishlist/index/cart'),
                'addToCartButtonSelector' => $this->getAddToCartButtonSelector()
            ]
        ];

        return $this->jsonEncoder->encode(array_merge($optionsAjaxstyle, $options));
    }

    /**
     * @param $icon
     * @return string
     */
    public function getAjaxSidebarInitOptions($icon)
    {
        $options = [
            'icon'  => $icon,
            'texts' => [
                'loaderText' => __('Loading...'),
                'imgAlt'     => __('Loading...')
            ]
        ];

        return $this->jsonEncoder->encode($options);
    }

    /**
     * @return string
     */
    public function getAddToCartButtonSelector()
    {
        $class = $this->getScopeConfig('tsn_questionnaire/general/addtocart_btn_class');
        if (empty($class)) {
            $class = 'tocart';
        }
        return 'button.' . $class;
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getScopeConfig($path)
    {
        return $this->scopeConfig->getValue($path,
            ScopeInterface::SCOPE_STORE,
            $this->storeId
        );
    }

    /**
     * @param $price
     * @return float|int|string
     */
    public function getPriceWithCurrency($price)
    {
        if ($price) {
            return $this->pricingHelper->currency(number_format($price, 2, '.', ''), true, false);
        }
        return 0;
    }

    /**
     * @param $product
     * @param $size
     * @return string
     */
    public function getProductImageUrl($product, $size)
    {
        $imageSize = 'product_page_image_' . $size;
        if ($size == 'category') {
            $imageSize = 'category_page_list';
        }
        $imageUrl = $this->prdImageHelper->init($product, $imageSize)
            ->keepAspectRatio(TRUE)
            ->keepFrame(FALSE)
            ->getUrl();
        return $imageUrl;
    }
}
