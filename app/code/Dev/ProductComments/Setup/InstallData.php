<?php

namespace Dev\ProductComments\Setup;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;



class InstallData implements InstallDataInterface
{

    private $eavSetupFactory;

    public function __construct (EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }


    public function install (ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create ();
        $eavSetup->addAttribute (
            Product::ENTITY,
            'product_comments',
            [
                'group' => 'General',
                'type' => 'varchar',
                'label' => 'Product Comments',
                'input' => 'select',
                'source' => \Dev\ProductComments\Model\Attribute\Source\Comments::class,
                'frontend' => \Dev\ProductComments\Model\Attribute\Frontend\Comments::class,
                'required' => false,
                'sort_order' => 50,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'visible' => true,
                'is_html_allowed_on_front' => true,
                'visible_on_front' => false
            ]
        );
    }
}

