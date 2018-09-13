<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

use TSN\ProductQuestionnaire\Api\Model\GroupInterface;
use TSN\ProductQuestionnaire\Api\Model\Schema\GroupInterface as SchemaInterface;
use TSN\ProductQuestionnaire\Model\ResourceModel\Group as ResourceModel;

class Group extends AbstractModel implements GroupInterface, IdentityInterface
{
    /** {@inheritdoc} */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /** {@inheritdoc} */
    public function getCodeGroup()
    {
        return $this->getData(SchemaInterface::GROUP_CODE);
    }

    /** {@inheritdoc} */
    public function setCodeGroup($codeGroup)
    {
        $this->setData(SchemaInterface::GROUP_CODE, $codeGroup);

        return $this;
    }

    /** {@inheritdoc} */
    public function getLabelGroup()
    {
        return $this->getData(SchemaInterface::GROUP_LABEL);
    }

    /** {@inheritdoc} */
    public function setLabelGroup($labelGroup)
    {
        $this->setData(SchemaInterface::GROUP_LABEL, $labelGroup);

        return $this;
    }

    /** {@inheritdoc} */
    public function getIsActive()
    {
        return $this->getData(SchemaInterface::ACTIVE_FIELD);
    }

    /** {@inheritdoc} */
    public function setIsActive($isActive)
    {
        return $this->setData(SchemaInterface::ACTIVE_FIELD, $isActive);
    }

    /** {@inheritdoc} */
    public function getIdentities()
    {
        return [sprintf('%s_%d', GroupInterface::CACHE_TAG, (int) $this->getId())];
    }
}