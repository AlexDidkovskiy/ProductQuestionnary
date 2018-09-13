<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Ui\DataProvider\Product;

use Magento\Framework\Api\Filter;
use Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider as BaseProvider;
  
class ProductDataProvider extends BaseProvider
{
    /** {@inheritdoc}*/
	public function addFilter(Filter $filter)
   {
            if($filter->getField()=='category_id'){
				$this->getCollection()->addCategoriesFilter(array('in' => $filter->getValue()));
			}
	        elseif (isset($this->addFilterStrategies[$filter->getField()])) {
	            $this->addFilterStrategies[$filter->getField()]
	                ->addFilter(
	                    $this->getCollection(),
	                    $filter->getField(),
	                    [$filter->getConditionType() => $filter->getValue()]
	                );
	        } else {
	            parent::addFilter($filter);
	        }
	}
}
