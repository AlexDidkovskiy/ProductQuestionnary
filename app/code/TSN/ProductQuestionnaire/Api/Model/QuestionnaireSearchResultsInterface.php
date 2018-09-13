<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Api\Model;

use Magento\Framework\Api\SearchResultsInterface;

interface QuestionnaireSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get data list.
     *
     * @return \TSN\ProductQuestionnaire\Api\Model\QuestionnaireInterface[]
     */
    public function getItems();

    /**
     * Set data list.
     *
     * @param \TSN\ProductQuestionnaire\Api\Model\QuestionnaireInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
