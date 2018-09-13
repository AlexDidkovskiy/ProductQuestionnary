<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */
namespace TSN\ProductQuestionnaire\Controller\Adminhtml\Question;

use TSN\ProductQuestionnaire\Controller\Adminhtml\Question;
use TSN\ProductQuestionnaire\Api\Model\Schema\QuestionnaireInterface as SchemaQuestionnaireInterface;

class Edit extends Question
{
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $questionId = $this->getRequest()->getParam(SchemaQuestionnaireInterface::ID_FIELD);
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('TSN_ProductQuestionnairee::edit')
            ->addBreadcrumb(__('Question'), __('Question'))
            ->addBreadcrumb(__('Manage Question'), __('Manage Question'));

        if ($questionId === null) {
            $resultPage->addBreadcrumb(__('New Question'), __('New Question'));
            $resultPage->getConfig()->getTitle()->prepend(__('New Question'));
        } else {
            $resultPage->addBreadcrumb(__('Edit Question'), __('Edit Question'));
            $resultPage->getConfig()->getTitle()->prepend(
                $this->questionRepository->getById($questionId)->getQuestion()
            );
        }
        return $resultPage;
    }
}
