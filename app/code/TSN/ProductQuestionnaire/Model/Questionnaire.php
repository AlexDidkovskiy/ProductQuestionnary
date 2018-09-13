<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

use TSN\ProductQuestionnaire\Api\Model\QuestionnaireInterface;
use TSN\ProductQuestionnaire\Api\Model\Schema\QuestionnaireInterface as SchemaInterface;
use TSN\ProductQuestionnaire\Model\ResourceModel\Questionnaire as ResourceModel;

class Questionnaire extends AbstractModel implements QuestionnaireInterface, IdentityInterface
{
    /** {@inheritdoc} */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /** {@inheritdoc} */
    public function getQuestion()
    {
        return $this->getData(SchemaInterface::QUESTION_FIELD);
    }

    /** {@inheritdoc} */
    public function setQuestion($question)
    {
        $this->setData(SchemaInterface::QUESTION_FIELD, $question);

        return $this;
    }

    /** {@inheritdoc} */
    public function getGroup()
    {
        return $this->getData(SchemaInterface::GROUP_FIELD);
    }

    /** {@inheritdoc} */
    public function setGroup($group)
    {
        $this->setData(SchemaInterface::GROUP_FIELD, $group);

        return $this;
    }

    /** {@inheritdoc} */
    public function getTypeQuestion()
    {
        return $this->getData(SchemaInterface::TYPE_QUESTION_FIELD);
    }

    /** {@inheritdoc} */
    public function setTypeQuestion($typeQuestion)
    {
        $this->setData(SchemaInterface::TYPE_QUESTION_FIELD, $typeQuestion);

        return $this;
    }

    /** {@inheritdoc} */
    public function getAnswersVariants()
    {
        return $this->getData(SchemaInterface::ANSWERS_VARIANTS_FIELD);
    }

    /** {@inheritdoc} */
    public function setAnswersVariants($answersVariants)
    {
        $this->setData(SchemaInterface::ANSWERS_VARIANTS_FIELD, $answersVariants);

        return $this;
    }

    /** {@inheritdoc} */
    public function getIsActive()
    {
        return $this->getData(SchemaInterface::ACTIVE_FIELD);
    }

    /** {@inheritdoc} */
    public function setIsActive($isActive)
    {
        return $this->setData(SchemaInterface::ACTIVE_FIELD, $isActive);
    }

    /** {@inheritdoc} */
    public function getIdentities()
    {
        return [sprintf('%s_%d', QuestionnaireInterface::CACHE_TAG, (int) $this->getId())];
    }
}