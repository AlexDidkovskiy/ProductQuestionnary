<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Api\Model\Schema;


interface QuestionnaireInterface
{
    const TABLE_NAME                = 'tsn_questionnaire';

    const ID_FIELD                  = 'questionnaire_id';
    const QUESTION_FIELD            = 'question';
    const GROUP_FIELD               = 'group';
    const TYPE_QUESTION_FIELD       = 'type_question';
    const ANSWERS_VARIANTS_FIELD    = 'answers_variants';
    const CREATED_FIELD             = 'created_at';
    const ACTIVE_FIELD              = 'is_active';
}