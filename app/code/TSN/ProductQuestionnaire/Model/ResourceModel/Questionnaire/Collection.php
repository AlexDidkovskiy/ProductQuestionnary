<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Model\ResourceModel\Questionnaire;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

use TSN\ProductQuestionnaire\Model\Questionnaire as Model;
use TSN\ProductQuestionnaire\Model\ResourceModel\Questionnaire as ResourceModel;
use TSN\ProductQuestionnaire\Api\Model\Schema\QuestionnaireInterface as SchemaQuestionnaireInterface;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = SchemaQuestionnaireInterface::ID_FIELD;

    /**
     * Collection initialisation
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init(Model::class, ResourceModel::class);
    }
}