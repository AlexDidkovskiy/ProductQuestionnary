<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Model\ResourceModel\Group;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;


use TSN\ProductQuestionnaire\Api\Model\Schema\GroupInterface as SchemaGroupInterface;
use TSN\ProductQuestionnaire\Model\Group as Model;
use TSN\ProductQuestionnaire\Model\ResourceModel\Group as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = SchemaGroupInterface::ID_FIELD;

    /**
     * Collection initialisation
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init(Model::class, ResourceModel::class);
    }

    /** {@inheritdoc} */
    public function toOptionArray()
    {
        $groupList = parent::_toOptionArray(SchemaGroupInterface::GROUP_CODE, SchemaGroupInterface::GROUP_LABEL);
        array_unshift($groupList, ['value'=>'default', 'label' =>'Default' ]);
        return $groupList;
    }
}