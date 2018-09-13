<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */
namespace TSN\ProductQuestionnaire\Controller\Adminhtml\Group;

use TSN\ProductQuestionnaire\Controller\Adminhtml\Group;
use TSN\ProductQuestionnaire\Api\Model\Schema\GroupInterface as SchemaGroupInterface;

class Edit extends Group
{
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $groupQuestionId = $this->getRequest()->getParam(SchemaGroupInterface::ID_FIELD);
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('TSN_ProductQuestionnairee::edit')
            ->addBreadcrumb(__('Group'), __('Group'))
            ->addBreadcrumb(__('Manage Group'), __('Manage Group'));

        if ($groupQuestionId === null) {
            $resultPage->addBreadcrumb(__('New Group'), __('New Group'));
            $resultPage->getConfig()->getTitle()->prepend(__('New Group'));
        } else {
            $resultPage->addBreadcrumb(__('Edit Group'), __('Edit Group'));
            $resultPage->getConfig()->getTitle()->prepend(
                $this->groupQuestionRepository->getById($groupQuestionId)->getGroup()
            );
        }
        return $resultPage;
    }
}
