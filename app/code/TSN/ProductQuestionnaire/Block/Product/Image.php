<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Block\Product;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Catalog\Api\ProductRepositoryInterface;

use TSN\ProductQuestionnaire\Helper\Data;

class Image extends Template
{
    protected $coreRegistry = null;

    /** @var ProductRepositoryInterface */
    protected $productRepository;

    /** @var Configurable */
    protected $configurableProduct;

    /** @var Data */
    protected $questionnaireHelper;

    /**
     * Image constructor.
     * @param Context $context
     * @param Registry $registry
     * @param ProductRepositoryInterface $productRepository
     * @param Configurable $configurableProduct
     * @param Data $questionnaireHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ProductRepositoryInterface $productRepository,
        Configurable $configurableProduct,
        Data $questionnaireHelper,
        array $data
    ) {
        parent::__construct($context, $data);
        $this->coreRegistry        = $registry;
        $this->productRepository   = $productRepository;
        $this->configurableProduct = $configurableProduct;
        $this->questionnaireHelper = $questionnaireHelper;
    }

    /** @return string */
    public function getImageUrl()
    {
        $color = $this->_request->getParam('color');
        $attributeOptions = [93 => $color];
        $productId = $this->coreRegistry->registry('current_product')->getId();
        $product = $this->productRepository->getById($productId);
        if ($product->getTypeId() == Configurable::TYPE_CODE) {
            $assPro = $this->configurableProduct->getProductByAttributes($attributeOptions, $product);
            if (!empty($assPro)) {
                $imageUrl = $this->questionnaireHelper->getProductImageUrl($assPro, 'category');
            } else {
                $imageUrl = $this->questionnaireHelper->getProductImageUrl($product, 'category');
            }
        } else {
            $imageUrl = $this->questionnaireHelper->getProductImageUrl($product, 'category');
        }
        return $imageUrl;
    }
}