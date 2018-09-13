<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Controller\Adminhtml\Group;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Ui\Component\MassAction\Filter;

use TSN\ProductQuestionnaire\Api\Model\GroupRepositoryInterface;
use TSN\ProductQuestionnaire\Controller\Adminhtml\Group;
use TSN\ProductQuestionnaire\Model\Group as GroupModel;
use TSN\ProductQuestionnaire\Model\ResourceModel\Group\CollectionFactory;

abstract class MassAction extends Group
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * @var ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var string
     */
    protected $successMessage;

    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * MassAction constructor.
     *
     * @param Filter $filter
     * @param Registry $registry
     * @param GroupRepositoryInterface $groupRepository
     * @param PageFactory $resultPageFactory
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param ForwardFactory $resultForwardFactory
     * @param $successMessage
     * @param $errorMessage
     */
    public function __construct(
        Filter $filter,
        Registry $registry,
        GroupRepositoryInterface $groupRepository,
        PageFactory $resultPageFactory,
        Context $context,
        CollectionFactory $collectionFactory,
        ForwardFactory $resultForwardFactory,
        $successMessage,
        $errorMessage
    ) {
        $this->filter                   = $filter;
        $this->groupRepository          = $groupRepository;
        $this->collectionFactory        = $collectionFactory;
        $this->resultForwardFactory     = $resultForwardFactory;
        $this->successMessage           = $successMessage;
        $this->errorMessage             = $errorMessage;
        parent::__construct($registry, $groupRepository, $resultPageFactory, $resultForwardFactory, $context);
    }

    /**
     * @param GroupModel $data
     * @return mixed
     */
    abstract protected function massAction(GroupModel $data);

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        try {
            $factory = $this->collectionFactory->create();
            $collection = $this->filter->getCollection($factory);
            $collectionSize = $collection->getSize();
            foreach ($collection as $data) {
                $this->massAction($data);
            }
            $this->messageManager->addSuccessMessage(__($this->successMessage, $collectionSize));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __($this->errorMessage));
        }
        $redirectResult = $this->resultRedirectFactory->create();
        $redirectResult->setPath('questionnaire/group/index');
        return $redirectResult;
    }
}
