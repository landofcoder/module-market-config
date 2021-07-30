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
        $table_lof_marketplace_config = $setup->getConnection()
            ->newTable($setup->getTable('lof_marketplace_config'))
            ->addColumn(
                'config_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => false,
                    'nullable' => false,
                    'primary' => true
                ],
                'Entity ID'
            )->addColumn(
                'seller_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => false,
                    'identity' => false
                ],
                'Seller ID'
            )->addColumn(
                'scope',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                8,
                [
                    'nullable' => true
                ],
                'Resource ID'
            )->addColumn(
                'scope_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => false,
                    'default' => 0
                ],
                'Config Scope ID	'
            )->addColumn(
                'path',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false,
                    'default' => 'general'
                ],
                'Config Path'
            )->addColumn(
                'value',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '1M',
                [
                    'nullable' => true
                ],
                'Config Value'
            )->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable' => false,
                    'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE
                ],
                'Updated At'
            )->addIndex(
                $setup->getIdxName('lof_marketplace_config', ['seller_id']),
                ['seller_id']
            )->addIndex(
                $setup->getIdxName('lof_marketplace_config', ['scope']),
                ['scope']
            )->addIndex(
                $setup->getIdxName('lof_marketplace_config', ['scope_id']),
                ['scope_id']
            )->addIndex(
                $setup->getIdxName('lof_marketplace_config', ['path']),
                ['path']
            )->addForeignKey(
                $setup->getFkName(
                    'lof_marketplace_config',
                    'seller_id',
                    'lof_marketplace_seller',
                    'seller_id'
                ),
                'seller_id',
                $setup->getTable('lof_marketplace_seller'),
                'seller_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            );

        $setup->getConnection()->createTable($table_lof_marketplace_config);
    }
}
