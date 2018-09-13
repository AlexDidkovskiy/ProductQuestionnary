<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Model\ResourceModel;


use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

use TSN\ProductQuestionnaire\Api\Model\Schema\GroupInterface;

class Group extends AbstractDb
{

    /** {@inheritdoc} */
    protected function _construct()
    {
        $this->_init(GroupInterface::TABLE_NAME, GroupInterface::ID_FIELD);
    }
}