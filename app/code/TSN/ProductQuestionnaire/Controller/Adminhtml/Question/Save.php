<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Controller\Adminhtml\Question;

use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Message\Manager;
use Magento\Framework\Api\DataObjectHelper;

use TSN\ProductQuestionnaire\Api\Model\QuestionnaireRepositoryInterface;
use TSN\ProductQuestionnaire\Api\Model\QuestionnaireInterface;
use TSN\ProductQuestionnaire\Api\Model\Schema\QuestionnaireInterface as SchemaQuestionnaireInterface;
use TSN\ProductQuestionnaire\Api\Model\QuestionnaireInterfaceFactory;
use TSN\ProductQuestionnaire\Controller\Adminhtml\Question;

class Save extends Question
{
    /**
     * @var Manager
     */
    protected $messageManager;

    /**
     * @var QuestionnaireRepositoryInterface
     */
    protected $questionRepository;

    /**
     * @var QuestionnaireInterfaceFactory
     */
    protected $questionFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    public function __construct(
        Registry $registry,
        QuestionnaireRepositoryInterface $questionRepository,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        Manager $messageManager,
        QuestionnaireInterfaceFactory $questionFactory,
        DataObjectHelper $dataObjectHelper,
        Context $context
    ) {
        $this->messageManager   = $messageManager;
        $this->questionFactory      = $questionFactory;
        $this->questionRepository   = $questionRepository;
        $this->dataObjectHelper  = $dataObjectHelper;
        parent::__construct($registry, $questionRepository, $resultPageFactory, $resultForwardFactory, $context);
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
            $id = $this->getRequest()->getParam(SchemaQuestionnaireInterface::ID_FIELD);
            if ($id) {
                $model = $this->questionRepository->getById($id);
            } else {
                unset($data[SchemaQuestionnaireInterface::ID_FIELD]);
                $model = $this->questionFactory->create();
            }

            try {
                $this->dataObjectHelper->populateWithArray($model, $data, QuestionnaireInterface::class);
                $this->questionRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved this question.'));
                $this->_getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', [SchemaQuestionnaireInterface::ID_FIELD => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', [SchemaQuestionnaireInterface::ID_FIELD => $this->getRequest()->getParam(SchemaQuestionnaireInterface::ID_FIELD)]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
