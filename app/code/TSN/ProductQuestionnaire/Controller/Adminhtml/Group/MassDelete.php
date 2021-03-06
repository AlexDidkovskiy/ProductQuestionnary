<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Controller\Adminhtml\Group;

use TSN\ProductQuestionnaire\Model\Group;

class MassDelete extends MassAction
{
    /**
     * @param Group $data
     * @return $this
     */
    protected function massAction(Group $data)
    {
        $this->groupRepository->delete($data);
        return $this;
    }
}
