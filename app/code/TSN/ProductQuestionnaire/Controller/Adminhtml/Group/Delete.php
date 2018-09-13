<?php
/**
* @author TSN-Media Team
* @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
* @package TSN_ProductQuestionnaire
*/

namespace TSN\ProductQuestionnaire\Controller\Adminhtml\Group;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

use TSN\ProductQuestionnaire\Controller\Adminhtml\Group;
use TSN\ProductQuestionnaire\Api\Model\Schema\GroupInterface as SchemaGroupInterface;

class Delete extends Group
{
    /**
     * Delete the question entity
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $groupQuestionId = $this->getRequest()->getParam(SchemaGroupInterface::ID_FIELD);
        if ($groupQuestionId) {
            try {
                $this->groupRepository->deleteById($groupQuestionId);
                $this->messageManager->addSuccessMessage(__('The group has been deleted.'));
                $resultRedirect->setPath('questionnaire/group/index');
                return $resultRedirect;
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('The group no longer exists.'));
                return $resultRedirect->setPath('questionnaire/group/index');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('questionnaire/group/index', [SchemaGroupInterface::ID_FIELD => $groupQuestionId]);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('There was a problem deleting the question'));
                return $resultRedirect->setPath('questionnaire/group/edit', [SchemaGroupInterface::ID_FIELD => $groupQuestionId]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find the group to delete.'));
        $resultRedirect->setPath('questionnaire/group/index');
        return $resultRedirect;
    }
}
