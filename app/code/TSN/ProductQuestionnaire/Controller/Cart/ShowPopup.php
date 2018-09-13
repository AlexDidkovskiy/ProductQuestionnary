<?php

namespace TSN\ProductQuestionnaire\Controller\Cart;

use Psr\Log\LoggerInterface;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart as CheckoutCart;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Checkout\Controller\Cart;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Checkout\Model\Session;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Registry;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Escaper;
use Magento\Checkout\Helper\Cart as CheckoutHelper;

use TSN\ProductQuestionnaire\Helper\Data as QuestionnaireHelper;

class ShowPopup extends Cart
{
    /** @var Registry|null */
    protected $coreRegistry = null;

    /** @var ProductRepositoryInterface */
    protected $productRepository;

    /** @var JsonHelper */
    protected $jsonHelper;

    /** @var LoggerInterface */
    protected $loggerInterface;

    /** @var Escaper */
    protected $escaper;

    /** @var CheckoutHelper */
    protected $checkoutHelper;

    /** @var StoreManagerInterface */
    protected $storeManager;

    /** @var QuestionnaireHelper */
    protected $questionnaireData;

    /**
     * ShowPopup constructor.
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param Session $checkoutSession
     * @param StoreManagerInterface $storeManager
     * @param Validator $formKeyValidator
     * @param CheckoutCart $cart
     * @param Registry $registry
     * @param ProductRepositoryInterface $productRepository
     * @param JsonHelper $jsonHelper
     * @param LoggerInterface $loggerInterface
     * @param Escaper $escaper
     * @param CheckoutHelper $checkoutHelper
     * @param QuestionnaireHelper $questionnaireData
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        Session $checkoutSession,
        StoreManagerInterface $storeManager,
        Validator $formKeyValidator,
        CheckoutCart $cart,
        Registry $registry,
        ProductRepositoryInterface $productRepository,
        JsonHelper $jsonHelper,
        LoggerInterface $loggerInterface,
        Escaper $escaper,
        CheckoutHelper $checkoutHelper,
        QuestionnaireHelper $questionnaireData
    ) {
        parent::__construct(
            $context,
            $scopeConfig,
            $checkoutSession,
            $storeManager,
            $formKeyValidator,
            $cart
        );
        $this->coreRegistry      = $registry;
        $this->productRepository = $productRepository;
        $this->jsonHelper        = $jsonHelper;
        $this->loggerInterface   = $loggerInterface;
        $this->escaper           = $escaper;
        $this->checkoutHelper    = $checkoutHelper;
        $this->storeManager      = $storeManager;
        $this->questionnaireData = $questionnaireData;
    }

    /**
     * Add product to shopping cart action
     * @return \Magento\Framework\Controller\Result\Redirect|ShowPopup
     */
    public function execute()
    {

        $params = $this->getRequest()->getParams();

        try {
            $product = $this->_initProduct();
            if (!empty($params['questionnaire_error'])) {
                $this->coreRegistry->register('product', $product);
                $this->coreRegistry->register('current_product', $product);

                $htmlPopup = $this->questionnaireData->getErrorHtml($product);
                $result['error'] = true;
                $result['html_popup'] = $htmlPopup;

                $this->getResponse()->representJson(
                    $this->jsonHelper->jsonEncode($result)
                );

                return;
            }

            if (!empty($params['questionnaire_success'])) {
                $this->coreRegistry->register('product', $product);
                $this->coreRegistry->register('current_product', $product);

                $htmlPopup = $this->questionnaireData->getSuccessHtml($product);
                $result['success'] = true;
                $result['html_popup'] = $htmlPopup;

                $this->getResponse()->representJson(
                    $this->jsonHelper->jsonEncode($result)
                );

                return;
            }

            /* return options popup content when product type is grouped */
            if ($product->getHasOptions()
                || ($product->getTypeId() == 'grouped' && !isset($params['super_group']))
                || ($product->getTypeId() == 'configurable' && !isset($params['super_attribute']))
                || ($product->getTypeId() == 'bundle' && !isset($params['bundle_option']))
            ) {
                $this->coreRegistry->register('product', $product);
                $this->coreRegistry->register('current_product', $product);

                $htmlPopup = $this->questionnaireData->getOptionsPopupHtml($product);
                $result['success'] = true;
                $result['html_popup'] = $htmlPopup;

                $this->getResponse()->representJson(
                    $this->jsonHelper->jsonEncode($result)
                );
                return;
            } elseif ($product->getData('question') && !isset($params['tsn'])) {
                $this->coreRegistry->register('product', $product);
                $this->coreRegistry->register('current_product', $product);

                $htmlPopup = $this->questionnaireData->getQuestionnaireFormHtml($product);
                $result['success'] = true;
                $result['html_popup'] = $htmlPopup;

                $this->getResponse()->representJson(
                    $this->jsonHelper->jsonEncode($result)
                );
                return;
            } else {
                return $this->_forward(
                    'add',
                    'cart',
                    'checkout',
                    $params);
            }
        } catch (LocalizedException $e) {
            if ($this->_checkoutSession->getUseNotice(true)) {
                $this->messageManager->addNoticeMessage(
                    $this->escaper->escapeHtml($e->getMessage())
                );
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $this->messageManager->addErrorMessage(
                        $this->escaper->escapeHtml($message)
                    );
                }
            }

            $url = $this->_checkoutSession->getRedirectUrl(true);

            if (!$url) {
                $cartUrl = $this->checkoutHelper->getCartUrl();
                $url = $this->_redirect->getRedirectUrl($cartUrl);
            }

            return $this->goBack($url);

        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('We can\'t add this item to your shopping cart right now.'));
            $this->loggerInterface->critical($e);
            return $this->goBack();
        }
    }

    /**
     * Initialize product instance from request data
     * @return bool|\Magento\Catalog\Api\Data\ProductInterface
     */
    protected function _initProduct()
    {
        $productId = (int)$this->getRequest()->getParam('product');
        if ($productId) {
            $storeId = $this->storeManager->getStore()->getId();
            try {
                $product = $this->productRepository->getById($productId, false, $storeId);
                return $product;
            } catch (NoSuchEntityException $e) {
                return false;
            }
        }
        return false;
    }

    /**
     * Resolve response
     * @param null $backUrl
     * @param null $product
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    protected function goBack($backUrl = null, $product = null)
    {
        if (!$this->getRequest()->isAjax()) {
            return parent::_goBack($backUrl);
        }

        $result = [];

        if ($backUrl || $backUrl = $this->getBackUrl()) {
            $result['backUrl'] = $backUrl;
        } else {
            if ($product && !$product->getIsSalable()) {
                $result['product'] = [
                    'statusText' => __('Out of stock')
                ];
            }
        }

        $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($result)
        );
    }
}