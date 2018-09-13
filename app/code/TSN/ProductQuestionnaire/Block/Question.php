<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\ResourceConnection;
use Magento\Catalog\Block\Product\View;

use TSN\ProductQuestionnaire\Model\ResourceModel\Questionnaire\CollectionFactory;
use TSN\ProductQuestionnaire\Helper\Data;

class Question extends Template
{

    /** @var ResourceConnection */
    protected $resource;

    /** @var View */
    protected $viewProduct;

    /** @var CollectionFactory */
    protected $collectionFactory;

    /** @var Data */
    protected $questionnaireHelper;

    /**
     * Question constructor.
     * @param Context $context
     * @param ResourceConnection $resource
     * @param CollectionFactory $collectionFactory
     * @param Data $questionnaireHelper
     * @param View $viewProduct
     * @param array $data
     */
    public function __construct(
        Context $context,
        ResourceConnection $resource,
        CollectionFactory $collectionFactory,
        Data $questionnaireHelper,

        View $viewProduct,
        array $data
    ) {
        $this->resource = $resource;
        $this->collectionFactory = $collectionFactory;
        $this->viewProduct = $viewProduct;
        $this->questionnaireHelper = $questionnaireHelper;

        parent::__construct(
            $context,
            $data
        );
    }

    /** @return \Magento\Catalog\Model\Product */
    public function getProduct()
    {
        $product = $this->viewProduct->getProduct();

        return $product;
    }

    /** @return \TSN\ProductQuestionnaire\Model\ResourceModel\Questionnaire\Collection */
    public function getAllQuestion()
    {
        $collection = $this->collectionFactory->create();

        return $collection;
    }

    /**
     * @param $group
     * @return $this
     */
    public function getQuestionByGroup($group)
    {
        $collection = $this->getAllQuestion()->addFieldToFilter('group', $group);

        return $collection;
    }

    /**
     * @return mixed|string
     */
    public function getQuestionForm()
    {
        $message = $this->questionnaireHelper->getScopeConfig('tsn_questionnaire/general/question_form');
        if (!$message) {
            $message = 'Before buying this product, answer a few questions';
        }
        return $message;
    }
}