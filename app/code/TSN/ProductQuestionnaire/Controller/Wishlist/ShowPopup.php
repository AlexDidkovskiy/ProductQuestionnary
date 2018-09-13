<?php

namespace TSN\ProductQuestionnaire\Controller\Wishlist;

use Magento\Catalog\Model\Product\Exception as ProductException;
use Magento\Framework\App\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Wishlist\Controller\AbstractIndex;
use Magento\Wishlist\Controller\WishlistProviderInterface;
use Magento\Wishlist\Model\LocaleQuantityProcessor;
use Magento\Wishlist\Model\ItemFactory;
use Magento\Checkout\Model\Cart as CheckoutCart;
use Magento\Wishlist\Model\Item\OptionFactory;
use Magento\Catalog\Helper\Product;
use Magento\Framework\Escaper;
use Magento\Wishlist\Helper\Data;
use Magento\Checkout\Helper\Cart as CheckoutHelper;
use Magento\Framework\Registry;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Exception\LocalizedException;

use TSN\ProductQuestionnaire\Helper\Data as QuestionnaireHelper;


class ShowPopup extends AbstractIndex
{
    /** @var WishlistProviderInterface */
    protected $wishlistProvider;

    /** @var LocaleQuantityProcessor */
    protected $quantityProcessor;

    /** @var ItemFactory */
    protected $itemFactory;

    /** @var CheckoutCart */
    protected $cart;

    /** @var CheckoutHelper */
    protected $checkoutHelper;

    /** @var Product */
    protected $productHelper;

    /** @var Escaper */
    protected $escaper;

    /** @var Data */
    protected $helper;

    /** @var Registry|null */
    protected $coreRegistry = null;

    /** @var JsonHelper */
    protected $jsonHelper;

    /** @var QuestionnaireHelper */
    protected $questionnaireData;

    /** @var OptionFactory */
    private $optionFactory;

    /**
     * ShowPopup constructor.
     * @param Context $context
     * @param WishlistProviderInterface $wishlistProvider
     * @param LocaleQuantityProcessor $quantityProcessor
     * @param ItemFactory $itemFactory
     * @param CheckoutCart $cart
     * @param OptionFactory $optionFactory
     * @param Product $productHelper
     * @param Escaper $escaper
     * @param Data $helper
     * @param CheckoutHelper $checkoutHelper
     * @param Registry $registry
     * @param JsonHelper $jsonHelper
     * @param QuestionnaireHelper $questionnaireData
     */
    public function __construct(
        Context $context,
        WishlistProviderInterface $wishlistProvider,
        LocaleQuantityProcessor $quantityProcessor,
        ItemFactory $itemFactory,
        CheckoutCart $cart,
        OptionFactory $optionFactory,
        Product $productHelper,
        Escaper $escaper,
        Data $helper,
        CheckoutHelper $checkoutHelper,
        Registry $registry,
        JsonHelper $jsonHelper,
        QuestionnaireHelper $questionnaireData
    ) {
        $this->wishlistProvider  = $wishlistProvider;
        $this->quantityProcessor = $quantityProcessor;
        $this->itemFactory       = $itemFactory;
        $this->cart              = $cart;
        $this->optionFactory     = $optionFactory;
        $this->productHelper     = $productHelper;
        $this->escaper           = $escaper;
        $this->helper            = $helper;
        $this->checkoutHelper    = $checkoutHelper;
        $this->questionnaireData = $questionnaireData;
        $this->coreRegistry      = $registry;
        $this->jsonHelper        = $jsonHelper;
        parent::__construct($context);
    }

    /**
     * Add product to shopping cart from wishlist action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();



        try {
            $itemId = (int)$params['item'];

            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            /* @var $item \Magento\Wishlist\Model\Item */
            $item = $this->itemFactory->create()->load($itemId);
            $product = $item->getProduct();

            if (!empty($params['questionnaire_error'])) {
                $this->coreRegistry->register('product', $product);
                $this->coreRegistry->register('current_product', $product);

                $htmlPopup = $this->questionnaireData->getErrorHtml($product);
                $result['error'] = true;
                $result['html_popup'] = $htmlPopup;
                $result['item'] = $itemId;

                $this->getResponse()->representJson(
                    $this->jsonHelper->jsonEncode($result)
                );

                return;
            }

            if (!$item->getId()) {
                $resultRedirect->setPath('*/*');
                return $resultRedirect;
            }
            $wishlist = $this->wishlistProvider->getWishlist($item->getWishlistId());
            if (!$wishlist) {
                $resultRedirect->setPath('*/*');
                return $resultRedirect;
            }



            if (!$product) {
                $resultRedirect->setPath('*/*');
                return $resultRedirect;
            }

            if (!empty($params['questionnaire_success'])) {
                $item->delete();
                $wishlist->save();

                $this->coreRegistry->register('product', $product);
                $this->coreRegistry->register('current_product', $product);
                $htmlPopup = $this->questionnaireData->getSuccessHtml($product);
                $result['success'] = true;
                $result['html_popup'] = $htmlPopup;
                $result['item'] = $itemId;

                $this->getResponse()->representJson(
                    $this->jsonHelper->jsonEncode($result)
                );

                return;
            }

            /* return options popup content when product type is grouped */
            if ($product->getHasOptions()
                || ($product->getTypeId() == 'grouped' && !isset($params['super_group']))
                || ($product->getTypeId() == 'configurable' && !isset($params['super_attribute']))
                || $product->getTypeId() == 'bundle'
            ) {
                $options = $this->optionFactory->create()->getCollection()->addItemFilter([$itemId]);
                $item->setOptions($options->getOptionsByItem($itemId));

                $buyRequest = $this->productHelper->addParamsToBuyRequest(
                    $this->getRequest()->getParams(),
                    ['current_config' => $item->getBuyRequest()]
                );
                $supperAttribute = $item->getBuyRequest()->getData('super_attribute');
                if (!empty($supperAttribute)) {
                    $item->mergeBuyRequest($buyRequest);
                    $item->addToCart($this->cart, true);
                    $this->cart->save()->getQuote()->collectTotals();
                    $item->delete();
                    $wishlist->save();

                    $this->coreRegistry->register('product', $product);
                    $this->coreRegistry->register('current_product', $product);
                    $htmlPopup = $this->questionnaireData->getSuccessHtml($product);
                    $result['success'] = true;
                    $result['html_popup'] = $htmlPopup;
                    $result['item'] = $itemId;
                    $result['addto'] = true;

                    $this->getResponse()->representJson(
                        $this->jsonHelper->jsonEncode($result)
                    );
                    return;
                } else {
                    $this->coreRegistry->register('product', $product);
                    $this->coreRegistry->register('current_product', $product);

                    $htmlPopup = $this->questionnaireData->getOptionsPopupHtml($product);
                    $result['success'] = true;
                    $result['html_popup'] = $htmlPopup;
                    $result['item'] = $itemId;

                    $this->getResponse()->representJson(
                        $this->jsonHelper->jsonEncode($result)
                    );

                    return;
                }

            } elseif ($product->getData('question')) {
                $options = $this->optionFactory->create()->getCollection()->addItemFilter([$itemId]);
                $item->setOptions($options->getOptionsByItem($itemId));

                $buyRequest = $this->productHelper->addParamsToBuyRequest(
                    $this->getRequest()->getParams(),
                    ['current_config' => $item->getBuyRequest()]
                );
                $supperAttribute = $item->getBuyRequest()->getData('super_attribute');
                if (!empty($supperAttribute)) {
                    $item->mergeBuyRequest($buyRequest);
                    $item->addToCart($this->cart, true);
                    $this->cart->save()->getQuote()->collectTotals();
                    $item->delete();
                    $wishlist->save();

                    $this->coreRegistry->register('product', $product);
                    $this->coreRegistry->register('current_product', $product);
                    $htmlPopup = $this->questionnaireData->getSuccessHtml($product);
                    $result['success'] = true;
                    $result['html_popup'] = $htmlPopup;
                    $result['item'] = $itemId;
                    $result['addto'] = true;

                    $this->getResponse()->representJson(
                        $this->jsonHelper->jsonEncode($result)
                    );
                    return;
                } else {
                    $this->coreRegistry->register('product', $product);
                    $this->coreRegistry->register('current_product', $product);

                    $htmlPopup = $this->questionnaireData->getQuestionnaireFormHtml($product);
                    $result['success'] = true;
                    $result['html_popup'] = $htmlPopup;
                    $result['item'] = $itemId;

                    $this->getResponse()->representJson(
                        $this->jsonHelper->jsonEncode($result)
                    );

                    return;
                }
            } else {
                $params['product'] = $product->getId();

                $this->getResponse()->representJson(
                    $this->jsonHelper->jsonEncode($params)
                );

                $this->_forward(
                    'add',
                    'cart',
                    'checkout',
                    $params);

                return;
            }
        } catch (ProductException $e) {
            $this->messageManager->addErrorMessage(__('This product(s) is out of stock.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addNoticeMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('We can\'t add the item to the cart right now.'));
        }

        $this->helper->calculate();
    }
}