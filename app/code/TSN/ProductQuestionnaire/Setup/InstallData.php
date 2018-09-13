<?php
/**
 * @author TSN-Media Team
 * @copyright Copyright (c) 2018 TSN-Media (https://tsn-media.com)
 * @package TSN_ProductQuestionnaire
 */

namespace TSN\ProductQuestionnaire\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Category;

class InstallData implements InstallDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        /**
         * Add attributes to the eav/attribute
         */

        $eavSetup->addAttribute(
            Product::ENTITY,
            'question',
            [
                'type'                      => 'int',
                'label'                     => 'Questionnaire',
                'input'                     => 'select',
                'source'                    => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global'                    => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible'                   => true,
                'required'                  => false,
                'user_defined'              => false,
                'default'                   => '1',
                'searchable'                => false,
                'filterable'                => false,
                'comparable'                => false,
                'visible_on_front'          => true,
                'used_in_product_listing'   => true,
                'unique'                    => false,
                'apply_to'                  => '',
                'is_used_in_grid'           => false,
                'is_visible_in_grid'        => false,
                'is_filterable_in_grid'     => true
            ]
        );

        $eavSetup->addAttribute(
            Product::ENTITY,
            'group_questionnaire',
            [
                'group'                     => 'General',
                'type'                      => 'varchar',
                'label'                     => 'Group of questionnaire',
                'input'                     => 'select',
                'source'                    => 'TSN\ProductQuestionnaire\Model\Config\Source\Group',
                'global'                    => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible'                   => true,
                'required'                  => false,
                'user_defined'              => false,
                'default'                   => '1',
                'searchable'                => false,
                'filterable'                => false,
                'comparable'                => false,
                'visible_on_front'          => true,
                'used_in_product_listing'   => true,
                'unique'                    => false,
                'apply_to'                  => '',
                'is_used_in_grid'           => false,
                'is_visible_in_grid'        => false,
                'is_filterable_in_grid'     => true
            ]
        );
    }
}
