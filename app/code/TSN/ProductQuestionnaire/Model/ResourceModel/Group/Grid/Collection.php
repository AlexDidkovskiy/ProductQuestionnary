<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Model\ResourceModel\Group\Grid;



use Magento\Framework\Search\AggregationInterface;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\Document;
use Magento\Framework\Api\SearchCriteriaInterface;

use TSN\ProductQuestionnaire\Model\ResourceModel\Group\Collection as GroupCollection;
use TSN\ProductQuestionnaire\Model\ResourceModel\Group;

class Collection extends GroupCollection implements SearchResultInterface
{
    /**
     * Aggregations
     *
     * @var \Magento\Framework\Search\AggregationInterface
     */
    protected $aggregations;

    protected function _construct()
    {
        $this->_init(Document::class, Group::class);
    }

    /** {@inheritdoc} */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /** {@inheritdoc} */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
    }

    /**
     * @return null
     */
    public function getSearchCriteria()
    {
        return null;
    }

    /** {@inheritdoc} */
    public function setSearchCriteria(SearchCriteriaInterface $searchCriteria = null)
    {
        return $this;
    }

    /** {@inheritdoc} */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /** {@inheritdoc} */
    public function setTotalCount($totalCount)
    {
        return $this;
    }

    /**
     * @param array|null $items
     * @return $this
     */
    public function setItems(array $items = null)
    {
        return $this;
    }
}