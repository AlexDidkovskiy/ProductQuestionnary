<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Api\Model;

use Magento\Framework\Api\SearchCriteriaInterface;

interface QuestionnaireRepositoryInterface
{

    /**
     * @param int $questionId
     * @return QuestionnaireInterface
     */
    public function getById($questionId);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \TSN\ProductQuestionnaire\Api\Model\QuestionnaireSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param QuestionnaireInterface $questionnaire
     * @return QuestionnaireInterface
     */
    public function save(QuestionnaireInterface $questionnaire);

    /**
     * @param QuestionnaireInterface $questionnaire
     * @return QuestionnaireRepositoryInterface
     */
    public function delete(QuestionnaireInterface $questionnaire);

    /**
     * @param int $questionId
     * @return QuestionnaireRepositoryInterface
     */
    public function deleteById($questionId);

    /**
     * @return QuestionnaireInterface
     */
    public function getQuestionnaireObject();
}