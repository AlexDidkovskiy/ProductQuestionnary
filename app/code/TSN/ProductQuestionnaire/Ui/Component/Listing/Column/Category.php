<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;

class Category extends Column
{
    /** @var StoreManagerInterface */
	protected $storeManager;

    /** @var CategoryRepositoryInterface */
	protected $categoryRepository;

    /** @var ProductRepositoryInterface */
	protected $productRepository;

    /**
     * Category constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ProductRepositoryInterface $productRepository
     * @param StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CategoryRepositoryInterface $categoryRepository,
        ProductRepositoryInterface $productRepository,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data       = []
    ) {
		parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->storeManager         = $storeManager;
        $this->categoryRepository   = $categoryRepository;
        $this->productRepository    = $productRepository;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource)
    {
		$fieldName = $this->getData('name');
        if (isset($dataSource['data']['items'])) {
			foreach ($dataSource['data']['items'] as & $item) {
				$productId=$item['entity_id'];
				$product=$this->productRepository->getById($productId);
                $categoryIds = $product->getCategoryIds();
				$categories=array();
                if(count($categoryIds) ){
					foreach($categoryIds as $categoryId){
                        $category = $this->categoryRepository->get($categoryId, $this->storeManager->getStore()->getId());
						$categories[]=$category->getName();
					}
           
                }
                $item[$fieldName]=implode(',',$categories);
			}
		}
        return $dataSource;
    }
}
