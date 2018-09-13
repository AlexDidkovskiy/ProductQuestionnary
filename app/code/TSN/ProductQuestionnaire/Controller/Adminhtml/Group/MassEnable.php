<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Controller\Adminhtml\Group;

use TSN\ProductQuestionnaire\Model\Group;

class MassEnable extends MassAction
{
    /**
     * @param Group $group
     * @return $this
     */
    protected function massAction(Group $group)
    {
        $group->setIsActive(true);
        $this->groupRepository->save($group);
        return $this;
    }
}
