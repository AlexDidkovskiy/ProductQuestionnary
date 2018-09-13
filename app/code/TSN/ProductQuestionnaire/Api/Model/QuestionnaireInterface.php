<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Api\Model;


interface QuestionnaireInterface
{
    const CACHE_TAG      = 'tsn_questionnaire';

    const REGISTRY_KEY   = 'tsn_questionnaire_question';

    /**
     * @return int|null
     */
    public function getId();

    /**
     * @param int $questionId
     * @return QuestionnaireInterface
     */
    public function setId($questionId);

    /**
     * @return string
     */
    public function getQuestion();

    /**
     * @param string $question
     * @return QuestionnaireInterface
     */
    public function setQuestion($question);

    /**
     * @return string
     */
    public function getGroup();

    /**
     * @param string $group
     * @return QuestionnaireInterface
     */
    public function setGroup($group);

    /**
     * @return string
     */
    public function getTypeQuestion();

    /**
     * @param string $typeQuestion
     * @return QuestionnaireInterface
     */
    public function setTypeQuestion($typeQuestion);

    /**
     * @return string
     */
    public function getAnswersVariants();

    /**
     * @param string $answersVariants
     * @return QuestionnaireInterface
     */
    public function setAnswersVariants($answersVariants);

    /**
     * @return bool|int
     */
    public function getIsActive();

    /**
     * @param $isActive
     * @return QuestionnaireInterface
     */
    public function setIsActive($isActive);

}