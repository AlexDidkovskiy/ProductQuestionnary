<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Checkout\Model\Cart;

class CartInfo extends Template
{
    /** @var Cart */
    protected $cart;

    /**
     * CartInfo constructor.
     * @param Context $context
     * @param Cart $cart
     * @param array $data
     */
    public function __construct(
        Context $context,
        Cart $cart,
        array $data
    ) {
        parent::__construct($context, $data);
        $this->cart = $cart;
    }

    /** @return int */
    public function getItemsCount()
    {
        return $this->cart->getItemsCount();
    }

    /** @return float|int */
    public function getItemsQty()
    {
        return $this->cart->getItemsQty();
    }

    /** @return float */
    public function getSubTotal()
    {
        return $this->cart->getQuote()->getSubtotal();
    }
}