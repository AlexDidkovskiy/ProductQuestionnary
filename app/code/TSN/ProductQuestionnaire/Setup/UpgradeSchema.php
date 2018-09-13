<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;

use TSN\ProductQuestionnaire\Api\Model\Schema\GroupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            /** create table 'tsn_questionnaire_group' */
            $table = $installer->getConnection()->newTable(
                $installer->getTable(GroupInterface::TABLE_NAME)
            )->addColumn(
                GroupInterface::ID_FIELD,
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Group of Product Questionnaire Record Id'
            )->addColumn(
                GroupInterface::GROUP_CODE,
                Table::TYPE_TEXT,
                '255',
                ['nullable' => false, 'un'],
                'Code of Group'
            )->addColumn(
                GroupInterface::GROUP_LABEL,
                Table::TYPE_TEXT,
                '255',
                ['nullable' => false],
                'Name of Questionnaire Group'
            )->addColumn(
                GroupInterface::ACTIVE_FIELD,
                Table::TYPE_SMALLINT,
                null,
                [],
                'Active Status'
            )->addIndex(
                $installer->getIdxName(
                    $installer->getTable(GroupInterface::TABLE_NAME),
                    [GroupInterface::GROUP_CODE],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                [GroupInterface::GROUP_CODE],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            )->addIndex(
                $setup->getIdxName(
                    $installer->getTable(Groupinterface::TABLE_NAME),
                    [
                        GroupInterface::GROUP_CODE,
                        GroupInterface::GROUP_LABEL
                    ],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                [
                    GroupInterface::GROUP_CODE,
                    GroupInterface::GROUP_LABEL
                ],
                ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
            )->setComment(
                'Row Data Table'
            );

            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
