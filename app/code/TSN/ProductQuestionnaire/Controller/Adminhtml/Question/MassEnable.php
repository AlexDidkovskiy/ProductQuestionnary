<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Controller\Adminhtml\Question;

use TSN\ProductQuestionnaire\Model\Questionnaire;

class MassEnable extends MassAction
{
    /**
     * @param Questionnaire $question
     * @return $this
     */
    protected function massAction(Questionnaire $question)
    {
        $question->setIsActive(true);
        $this->questionRepository->save($question);
        return $this;
    }
}
