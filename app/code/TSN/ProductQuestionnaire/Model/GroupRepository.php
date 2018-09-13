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

use TSN\ProductQuestionnaire\Api\Model\GroupInterface;
use TSN\ProductQuestionnaire\Api\Model\GroupInterfaceFactory;
use TSN\ProductQuestionnaire\Api\Model\GroupRepositoryInterface;
use TSN\ProductQuestionnaire\Api\Model\GroupSearchResultsInterfaceFactory;
use TSN\ProductQuestionnaire\Model\ResourceModel\Group\CollectionFactory;
use TSN\ProductQuestionnaire\Model\ResourceModel\Group as ResourceModel;
use TSN\ProductQuestionnaire\Api\Model\Schema\GroupInterface as SchemaGroupInterface;

class GroupRepository implements GroupRepositoryInterface
{

    /** @var CollectionFactory */
    protected $collectionFactory;

    /** @var ResourceModel */
    protected $resourceModel;

    /** @var GroupInterfaceFactory */
    protected $modelFactory;

    /** @var GroupSearchResultsInterfaceFactory */
    protected $searchResultsFactory;

    /** @var DataObjectHelper */
    protected $dataObjectHelper;

    /**
     * GroupRepository constructor.
     * @param ResourceModel $resourceModel
     * @param CollectionFactory $collectionFactory
     * @param GroupInterfaceFactory $factory
     * @param GroupSearchResultsInterfaceFactory $searchResultsInterfaceFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        ResourceModel $resourceModel,
        CollectionFactory $collectionFactory,
        GroupInterfaceFactory $factory,
        GroupSearchResultsInterfaceFactory $searchResultsInterfaceFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->resourceModel        = $resourceModel;
        $this->collectionFactory    = $collectionFactory;
        $this->modelFactory         = $factory;
        $this->searchResultsFactory = $searchResultsInterfaceFactory;
        $this->dataObjectHelper     = $dataObjectHelper;
    }

    /** @inheritdoc */
    public function getById($groupId)
    {
        if (!isset($this->instances[$groupId])) {
            $group = $this->modelFactory->create();
            $this->resourceModel->load($group, $groupId);

            if (!$group->getId()) {
                throw new NoSuchEntityException(__('Group with id "%1" does not exist.', $groupId));
            }
            $this->instances[$groupId] = $group;
        }
        return $this->instances[$groupId];
    }

    /** @inheritdoc */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var \TSN\ProductQuestionnaire\Api\Model\GroupSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \TSN\ProductQuestionnaire\Model\ResourceModel\Group\Collection $collection */
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
            $field = SchemaGroupInterface::ID_FIELD;
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
    public function save(GroupInterface $group)
    {
        try {
            $this->resourceModel->save($group);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the group: %1',
                $exception->getMessage()
            ));
        }
    }

    /** @inheritdoc */
    public function delete(GroupInterface $group)
    {
        $this->resourceModel->delete($group);

        return $this;
    }

    /** @inheritdoc */
    public function deleteById($groupId)
    {
        $group = $this->getById($groupId);

        return $this->delete($group);
    }

    /** @inheritdoc */
    public function getGroupObject()
    {
        return $this->modelFactory->create();
    }
}