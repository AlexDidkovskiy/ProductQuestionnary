<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

use TSN\ProductQuestionnaire\Api\Model\QuestionnaireInterface;
use TSN\ProductQuestionnaire\Api\Model\QuestionnaireInterfaceFactory;
use TSN\ProductQuestionnaire\Api\Model\QuestionnaireRepositoryInterface;
use TSN\ProductQuestionnaire\Api\Model\QuestionnaireSearchResultsInterfaceFactory;
use TSN\ProductQuestionnaire\Model\ResourceModel\Questionnaire\CollectionFactory;
use TSN\ProductQuestionnaire\Model\ResourceModel\Questionnaire as ResourceModel;
use TSN\ProductQuestionnaire\Api\Model\Schema\QuestionnaireInterface as SchemaQuestionnaireInterface;

class QuestionnaireRepository implements QuestionnaireRepositoryInterface
{

    /** @var CollectionFactory */
    protected $collectionFactory;

    /** @var ResourceModel */
    protected $resourceModel;

    /** @var QuestionnaireInterfaceFactory */
    protected $modelFactory;

    /** @var QuestionnaireSearchResultsInterfaceFactory */
    protected $searchResultsFactory;

    /** @var DataObjectHelper */
    protected $dataObjectHelper;

    /**
     * QuestionnaireRepository constructor.
     * @param ResourceModel $resourceModel
     * @param CollectionFactory $collectionFactory
     * @param QuestionnaireInterfaceFactory $factory
     * @param QuestionnaireSearchResultsInterfaceFactory $searchResultsInterfaceFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        ResourceModel $resourceModel,
        CollectionFactory $collectionFactory,
        QuestionnaireInterfaceFactory $factory,
        QuestionnaireSearchResultsInterfaceFactory $searchResultsInterfaceFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->resourceModel        = $resourceModel;
        $this->collectionFactory    = $collectionFactory;
        $this->modelFactory         = $factory;
        $this->searchResultsFactory = $searchResultsInterfaceFactory;
        $this->dataObjectHelper     = $dataObjectHelper;
    }

    /** @inheritdoc */
    public function getById($questionId)
    {
        if (!isset($this->instances[$questionId])) {
        $question = $this->modelFactory->create();
        $this->resourceModel->load($question, $questionId);

        if (!$question->getId()) {
            throw new NoSuchEntityException(__('Question with id "%1" does not exist.', $questionId));
        }
            $this->instances[$questionId] = $question;
        }
        return $this->instances[$questionId];
    }

    /** @inheritdoc */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var \TSN\ProductQuestionnaire\Api\Model\QuestionnaireSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \TSN\ProductQuestionnaire\Model\ResourceModel\Questionnaire\Collection $collection */
        $collection = $this->collectionFactory->create();

        //Add filters from root filter group to the collection
        /** @var FilterGroup $group */
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
        $sortOrders = $searchCriteria->getSortOrders();
        /** @var SortOrder $sortOrder */
        if ($sortOrders) {
            foreach ($searchCriteria->getSortOrders() as $sortOrder) {
                $field = $sortOrder->getField();
                $collection->addOrder(
                    $field,
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        } else {
            $field = SchemaQuestionnaireInterface::ID_FIELD;
            $collection->addOrder($field, 'ASC');
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        $data = [];
        foreach ($collection as $datum) {
            $dataDataObject = $this->collectionFactory->create();
            $this->dataObjectHelper->populateWithArray($dataDataObject, $datum->getData(), DataInterface::class);
            $data[] = $dataDataObject;
        }
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults->setItems($data);
    }

    /** @inheritdoc */
    public function save(QuestionnaireInterface $questionnaire)
    {
        try {
            $this->resourceModel->save($questionnaire);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the question: %1',
                $exception->getMessage()
            ));
        }
    }

    /** @inheritdoc */
    public function delete(QuestionnaireInterface $questionnaire)
    {
        $this->resourceModel->delete($questionnaire);

        return $this;
    }

    /** @inheritdoc */
    public function deleteById($questionId)
    {
        $question = $this->getById($questionId);

        return $this->delete($question);
    }

    /** @inheritdoc */
    public function getQuestionnaireObject()
    {
        return $this->modelFactory->create();
    }
}