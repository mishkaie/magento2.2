<?php

namespace Dev\ProductComments\Setup;

use Exception;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Model\ResourceModel\Page;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;
    /**
     * @var Page
     */
    private $page;

    public function __construct(PageRepositoryInterface $pageRepository, Page $page)
    {
        $this->pageRepository = $pageRepository;
        $this->page = $page;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1.2') < 0) {
            $this->addWidget();
        }
        $setup->endSetup();
    }

    private function addWidget(): void
    {
        try {
            $content = '{{widget type="Dev\ProductComments\Block\Widget\Products" products_count="10"}}';
            $page = $this->pageRepository->getById(2);
            $page->setContent($content);
            $this->page->save($page);
        } catch (Exception $e) {
        }
    }
}
