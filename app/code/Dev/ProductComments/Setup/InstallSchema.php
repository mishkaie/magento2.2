<?php

namespace Dev\ProductComments\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Zend_Db_Exception;

class InstallSchema implements InstallSchemaInterface
{

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        try {
            $table=$setup->getConnection()
                ->newTable($setup->getTable('product_comments'))

                    ->addColumn(
                        'comment_id',
                        Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'nullable' => false,
                            'primary' => true,
                            'unsigned' => true,
                        ],
                        'Comment ID'
                    )
                    ->addColumn(
                        'email',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable' => false,'default'=>''],
                        'Email'
                    )
                    ->addColumn(
                        'product_id',
                        Table::TYPE_INTEGER,
                        null,
                        ['unsigned' => true, 'nullable' => false],
                        'Product ID'
                    )
                    ->addColumn(
                        'comment',
                        Table::TYPE_TEXT,
                        '255',
                        ['nullable'=>false,'default'=>''],
                        'Comment'
                    )
                    ->addColumn(
                        'status',
                        Table::TYPE_TEXT,
                        255,
                        ['nullable' => false, 'default' => 'Not Approved'],
                        'Status'
                    )
                    ->addColumn(
                        'created_at',
                        Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                        'Created At'
                    )
                    ->setComment('Comments Table');
                $setup->getConnection()->createTable($table);
        } catch (Zend_Db_Exception $e) {
        }
    }
}
