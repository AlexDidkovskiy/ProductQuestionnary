<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;

use TSN\ProductQuestionnaire\Api\Model\Schema\QuestionnaireInterface;

class InstallSchema implements InstallSchemaInterface
{

    /** {@inheritdoc} */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /** create table 'tsn_questionnaire' */
        $table = $installer->getConnection()->newTable(
            $installer->getTable(QuestionnaireInterface::TABLE_NAME)
        )->addColumn(
            QuestionnaireInterface::ID_FIELD,
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Question of Product Record Id'
        )->addColumn(
            QuestionnaireInterface::QUESTION_FIELD,
            Table::TYPE_TEXT,
            '255',
            ['nullable' => false],
            'Question of Product'
        )->addColumn(
            QuestionnaireInterface::GROUP_FIELD,
            Table::TYPE_TEXT,
            '255',
            ['nullable' => false],
            'Questionnaire Group'
        )->addColumn(
            QuestionnaireInterface::TYPE_QUESTION_FIELD,
            Table::TYPE_TEXT,
            '255',
            ['nullable' => false],
            'Type of Question'
        )->addColumn(
            QuestionnaireInterface::ANSWERS_VARIANTS_FIELD,
            Table::TYPE_TEXT,
            '255',
            ['nullable' => false],
            'Variants of answers'
        )->addColumn(
            QuestionnaireInterface::CREATED_FIELD,
            Table::TYPE_TIMESTAMP,
            null,
            [
                'nullable' => false,
                'default' => Table::TIMESTAMP_INIT,
            ],
            'Creation Time'
        )->addColumn(
            QuestionnaireInterface::ACTIVE_FIELD,
            Table::TYPE_SMALLINT,
            null,
            [],
            'Active Status'
        )->addIndex(
            $setup->getIdxName(
                $installer->getTable(QuestionnaireInterface::TABLE_NAME),
                [
                    QuestionnaireInterface::QUESTION_FIELD,
                    QuestionnaireInterface::GROUP_FIELD
                ],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            [
                QuestionnaireInterface::QUESTION_FIELD,
                QuestionnaireInterface::GROUP_FIELD
            ],
            ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->setComment(
            'Row Data Table'
        );

        $installer->getConnection()->createTable($table);



        //Install additional column 'quote_question' to  table 'quote_item'

        // Get module table
        $tableQuoteItem = $setup->getTable('quote_item');

        // Check if the table already exists
        if ($setup->getConnection()->isTableExists($tableQuoteItem) == true) {
            // Declare data
            $columns = [
                'quote_question' => [
                    'type'     => Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment'  => 'Question of product',
                ],
            ];

            $connection = $setup->getConnection();
            foreach ($columns as $name => $definition) {
                $connection->addColumn($tableQuoteItem, $name, $definition);
            }

        }

        //Install additional column 'order_item_question' to  table 'sales_order_item'

        // Get module table
        $tableSalesOrder = $setup->getTable('sales_order_item');

        // Check if the table already exists
        if ($setup->getConnection()->isTableExists($tableSalesOrder) == true) {
            // Declare data
            $columns = [
                'order_item_question' => [
                    'type'     => Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment'  => 'Question of product',
                ],
            ];

            $connection = $setup->getConnection();
            foreach ($columns as $name => $definition) {
                $connection->addColumn($tableSalesOrder, $name, $definition);
            }

        }

        $setup->endSetup();
    }
}