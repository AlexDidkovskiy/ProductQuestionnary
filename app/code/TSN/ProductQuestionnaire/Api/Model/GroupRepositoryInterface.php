<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Api\Model;

use Magento\Framework\Api\SearchCriteriaInterface;

interface GroupRepositoryInterface
{

    /**
     * @param int $groupId
     * @return GroupInterface
     */
    public function getById($groupId);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \TSN\ProductQuestionnaire\Api\Model\GroupSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param GroupInterface $group
     * @return GroupInterface
     */
    public function save(GroupInterface $group);

    /**
     * @param GroupInterface $group
     * @return GroupRepositoryInterface
     */
    public function delete(GroupInterface $group);

    /**
     * @param int $groupId
     * @return GroupRepositoryInterface
     */
    public function deleteById($groupId);

    /**
     * @return GroupInterface
     */
    public function getGroupObject();
}