<?php
/**
* @author TSN-Media Team
* @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
* @package TSN_ProductQuestionnaire
*/

namespace TSN\ProductQuestionnaire\Controller\Adminhtml\Question;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

use TSN\ProductQuestionnaire\Controller\Adminhtml\Question;
use TSN\ProductQuestionnaire\Api\Model\Schema\QuestionnaireInterface as SchemaQuestionnaireInterface;

class Delete extends Question
{
    /**
     * Delete the question entity
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $questionId = $this->getRequest()->getParam(SchemaQuestionnaireInterface::ID_FIELD);
        if ($questionId) {
            try {
                $this->questionRepository->deleteById($questionId);
                $this->messageManager->addSuccessMessage(__('The question has been deleted.'));
                $resultRedirect->setPath('questionnaire/question/index');
                return $resultRedirect;
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('The question no longer exists.'));
                return $resultRedirect->setPath('questionnaire/question/index');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('questionnaire/question/index', [SchemaQuestionnaireInterface::ID_FIELD => $questionId]);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('There was a problem deleting the question'));
                return $resultRedirect->setPath('questionnaire/question/edit', [SchemaQuestionnaireInterface::ID_FIELD => $questionId]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find the question to delete.'));
        $resultRedirect->setPath('questionnaire/question/index');
        return $resultRedirect;
    }
}
