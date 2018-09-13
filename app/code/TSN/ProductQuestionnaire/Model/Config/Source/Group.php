<?php
/**
 * Created by PhpStorm.
 * User: alekseyd
 * Date: 06.07.18
 * Time: 15:33
 */

namespace TSN\ProductQuestionnaire\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Option\ArrayInterface;

use TSN\ProductQuestionnaire\Model\ResourceModel\Group\CollectionFactory;

class Group extends AbstractSource
    implements ArrayInterface
{

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * Group constructor.
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }


    /**
     * @return array
     */
    public function toArray()
    {
        $groups = [];

        foreach ($this->collectionFactory->create() as $itemGroup) {
            $groups[$itemGroup->getCodeGroup()] = $itemGroup->getLabelGroup();
        }

        return $groups;
    }

    /**
     * Options getter
     * @return array
     */
    final public function toOptionArray()
    {
        $groups = $this->toArray();
        $refactorGroup = [
            [
                'value' => 'default',
                'label' => 'Default Group'
            ]
        ];

        foreach ($groups as $key => $value) {
            $refactorGroup[] = [
                'value' => $key,
                'label' => $value
            ];
        }

        return $refactorGroup;
    }


    /**
     * @return array
     */
    public function getAllOptions()
    {
        return $this->toOptionArray();
    }
}