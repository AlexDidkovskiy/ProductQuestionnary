<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Api\Model;

use Magento\Framework\Api\SearchResultsInterface;

interface GroupSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get data list.
     *
     * @return \TSN\ProductQuestionnaire\Api\Model\GroupInterface[]
     */
    public function getItems();

    /**
     * Set data list.
     *
     * @param \TSN\ProductQuestionnaire\Api\Model\GroupInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
