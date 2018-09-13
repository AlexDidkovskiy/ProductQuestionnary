<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Api\Model\Schema;


interface GroupInterface
{
    const TABLE_NAME        = 'tsn_questionnaire_group';

    const ID_FIELD          = 'questionnaire_group_id';
    const GROUP_CODE        = 'code_group';
    const GROUP_LABEL       = 'label_group';
    const ACTIVE_FIELD      = 'is_active';
}