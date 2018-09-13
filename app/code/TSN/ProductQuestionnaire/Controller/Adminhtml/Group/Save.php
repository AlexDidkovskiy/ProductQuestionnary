<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Controller\Adminhtml\Group;

use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Message\Manager;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\LocalizedException;

use TSN\ProductQuestionnaire\Api\Model\GroupRepositoryInterface;
use TSN\ProductQuestionnaire\Api\Model\GroupInterface;
use TSN\ProductQuestionnaire\Api\Model\GroupInterfaceFactory;
use TSN\ProductQuestionnaire\Controller\Adminhtml\Group;
use TSN\ProductQuestionnaire\Api\Model\Schema\GroupInterface as SchemaInterface;

class Save extends Group
{
    /**
     * @var Manager
     */
    protected $messageManager;

    /**
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * @var GroupInterfaceFactory
     */
    protected $groupFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    public function __construct(
        Registry $registry,
        GroupRepositoryInterface $groupRepository,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        Manager $messageManager,
        GroupInterfaceFactory $groupFactory,
        DataObjectHelper $dataObjectHelper,
        Context $context
    ) {
        $this->messageManager    = $messageManager;
        $this->groupFactory      = $groupFactory;
        $this->groupRepository   = $groupRepository;
        $this->dataObjectHelper  = $dataObjectHelper;
        parent::__construct($registry, $groupRepository, $resultPageFactory, $resultForwardFactory, $context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $id = $this->getRequest()->getParam(SchemaInterface::ID_FIELD);
            if ($id) {
                $model = $this->groupRepository->getById($id);
            } else {
                unset($data[SchemaInterface::ID_FIELD]);
                $model = $this->groupFactory->create();
            }

            try {
                $this->dataObjectHelper->populateWithArray($model, $data, GroupInterface::class);
                $this->groupRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved this group.'));
                $this->_getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', [SchemaInterface::ID_FIELD => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', [SchemaInterface::ID_FIELD => $this->getRequest()->getParam(SchemaInterface::ID_FIELD)]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
