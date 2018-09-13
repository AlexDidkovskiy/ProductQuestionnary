<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Api\Model;


interface GroupInterface
{
    const CACHE_TAG      = 'tsn_questionnaire_group';

    const REGISTRY_KEY   = 'tsn_questionnaire_groupquestion';

    /**
     * @return int|null
     */
    public function getId();

    /**
     * @param $groupQuestionId
     * @return GroupInterface
     */
    public function setId($groupQuestionId);

    /**
     * @return string
     */
    public function getCodeGroup();

    /**
     * @param $codeGroup
     * @return GroupInterface
     */
    public function setCodeGroup($codeGroup);

    /**
     * @return string
     */
    public function getLabelGroup();

    /**
     * @param $labelGroup
     * @return GroupInterface
     */
    public function setLabelGroup($labelGroup);

    /**
     * @return bool|int
     */
    public function getIsActive();

    /**
     * @param $isActive
     * @return GroupInterface
     */
    public function setIsActive($isActive);

}