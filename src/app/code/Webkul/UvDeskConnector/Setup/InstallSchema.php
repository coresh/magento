<?php
/**
* Webkul Software.
*
* @category Webkul
* @package Webkul_UvDeskConnector
* @author Webkul
* @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
* @license https://store.webkul.com/license.html
*/

namespace Webkul\UvDeskConnector\Setup;
 
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
 
class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
 
        $table = $setup->getConnection()->newTable(
            $setup->getTable('wk_uvdeskcon_tickets_mapping')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Row Id'
        )->addColumn(
            'subject',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Ticket subject'
        )->addColumn(
            'ticket_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Unique id of ticket'
        )->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            [ 'nullable' => false, 
              'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT
            ],
            'Creation date of the tickets' 
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Requested name'
        )->addColumn(
            'due_by',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Due date of the tickets'
        )->addColumn(
            'priority',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Priority of the tickets'
        )->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Status of the tickets'
        )->addColumn(
            'agent',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Ticket assignee agent name'
        )->addColumn(
            'order_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' =>'no'],
            'Order id'
        )->setComment(
            'Tickets Mapping Table'
        );
        $setup->getConnection()->createTable($table);
        $setup->endSetup();
    }
}