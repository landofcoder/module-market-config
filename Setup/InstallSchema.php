<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * https://landofcoder.com/terms
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_MarketConfig
 * @copyright  Copyright (c) 2021 Landofcoder (https://www.landofcoder.com/)
 * @license    https://landofcoder.com/terms
 */

namespace Lof\MarketConfig\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @throws \Zend_Db_Exception
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();

        $table_lof_marketplace_seller_settings = $setup->getTable("lof_marketplace_seller_settings");

        $setup->getConnection()->addColumn(
            $table_lof_marketplace_seller_settings,
            'scope',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 8,
                'nullable' => true,
                'comment' => 'Resource ID'
            ]
        );

        $setup->getConnection()->addColumn(
            $table_lof_marketplace_seller_settings,
            'scope_id',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'nullable' => false,
                'unsigned' => true,
                'default' => 0,
                'comment' => 'Config Scope ID'
            ]
        );

        $setup->getConnection()->addColumn(
            $table_lof_marketplace_seller_settings,
            'path',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => false,
                'default' => 'general',
                'comment' => 'Config Path'
            ]
        );

        $setup->getConnection()->addColumn(
            $table_lof_marketplace_seller_settings,
            'value',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => '1M',
                'nullable' => true,
                'comment' => 'Config Value'
            ]
        );

        $setup->getConnection()->addColumn(
            $table_lof_marketplace_seller_settings,
            'updated_at',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                'nullable' => false,
                'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE,
                'comment' => 'Updated At'
            ]
        );

        $setup->getConnection()->addIndex(
            $table_lof_marketplace_seller_settings,
            $setup->getIdxName($table_lof_marketplace_seller_settings, ['seller_id']),
            ['seller_id']
        );

        $setup->getConnection()->addIndex(
            $table_lof_marketplace_seller_settings,
            $setup->getIdxName($table_lof_marketplace_seller_settings, ['scope']),
            ['scope']
        );

        $setup->getConnection()->addIndex(
            $table_lof_marketplace_seller_settings,
            $setup->getIdxName($table_lof_marketplace_seller_settings, ['scope_id']),
            ['scope_id']
        );

        $setup->getConnection()->addIndex(
            $table_lof_marketplace_seller_settings,
            $setup->getIdxName($table_lof_marketplace_seller_settings, ['path']),
            ['path']
        );

        $setup->getConnection()->addForeignKey(
            $setup->getFkName('lof_marketplace_seller_settings', 'seller_id', 'lof_marketplace_seller', 'seller_id'),
            'lof_marketplace_seller_settings',
            'seller_id',
            $setup->getTable('lof_marketplace_seller'),
            'seller_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        );

        $setup->endSetup();
    }
}
