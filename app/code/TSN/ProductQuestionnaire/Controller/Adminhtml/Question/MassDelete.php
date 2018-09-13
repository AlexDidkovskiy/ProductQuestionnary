<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Controller\Adminhtml\Question;

use TSN\ProductQuestionnaire\Model\Questionnaire;

class MassDelete extends MassAction
{
    /**
     * @param Questionnaire $data
     * @return $this
     */
    protected function massAction(Questionnaire $data)
    {
        $this->questionRepository->delete($data);
        return $this;
    }
}
