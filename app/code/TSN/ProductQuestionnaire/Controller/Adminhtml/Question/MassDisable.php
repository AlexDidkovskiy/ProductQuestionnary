<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Controller\Adminhtml\Question;

use TSN\ProductQuestionnaire\Model\Questionnaire;

class MassDisable extends MassAction
{
    /**
     * @param Questionnaire $data
     * @return $this
     */
    protected function massAction(Questionnaire $data)
    {
        $data->setIsActive(false);
        $this->questionRepository->save($data);
        return $this;
    }
}
