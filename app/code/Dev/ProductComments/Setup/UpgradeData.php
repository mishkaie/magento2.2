<?php

namespace Dev\ProductComments\Setup {

    use Magento\Framework\Exception\AlreadyExistsException;
    use \Magento\Store\Model\StoreManagerInterface;
    use Magento\Store\Model\StoreFactory;
    use Magento\Store\Model\ResourceModel\Store;
    use Magento\Store\Model\GroupFactory;
    use Magento\Store\Model\ResourceModel\Group;
    use Magento\Store\Model\WebsiteFactory;
    use Magento\Store\Model\ResourceModel\Website;
    use Exception;
    use Magento\Framework\Setup\ModuleContextInterface;
    use Magento\Framework\Setup\ModuleDataSetupInterface;
    use Magento\Framework\Setup\UpgradeDataInterface;
    use Magento\Cms\Api\PageRepositoryInterface;
    use Magento\Cms\Model\ResourceModel\Page;
    use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
    use Magento\Theme\Model\Config;
    use Magento\Theme\Model\ResourceModel\Theme\CollectionFactory;

    class UpgradeData implements UpgradeDataInterface
    {
        private $groupFactory;

        private $websiteFactory;
        /**
         * @var PageRepositoryInterface
         */
        private $pageRepository;
        /**
         * @var Page
         */
        private $page;
        /**
         * @var Website
         */
        private $website;
        /**
         * @var Group
         */
        private $group;
        /**
         * @var StoreManagerInterface
         */
        private $storeManagerInterface;
        /**
         * @var Store
         */
        private $store;
        /**
         * @var StoreFactory
         */
        private $storeFactory;
        /**
         * @var ConfigInterface
         */
        private $resourceConfig;
        /**
         * @var CollectionFactory
         */
        private $collectionFactory;
        /**
         * @var Config
         */
        private $config;

        public function __construct(
            ConfigInterface $resourceConfig,
            StoreManagerInterface $storeManagerInterface,
            Group $group,
            GroupFactory $groupFactory,
            WebsiteFactory $websiteFactory,
            PageRepositoryInterface $pageRepository,
            Page $page,
            Website $website,
            Store $store,
            StoreFactory $storeFactory,
            CollectionFactory $collectionFactory,
            Config $config
        ) {

            $this->groupFactory = $groupFactory;
            $this->websiteFactory = $websiteFactory;
            $this->pageRepository = $pageRepository;
            $this->page = $page;
            $this->website = $website;
            $this->group = $group;
            $this->storeManagerInterface = $storeManagerInterface;
            $this->store = $store;
            $this->storeFactory = $storeFactory;
            $this->resourceConfig = $resourceConfig;
            $this->collectionFactory = $collectionFactory;
            $this->config = $config;
        }

        public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context): void
        {
            $setup->startSetup();
            if (version_compare($context->getVersion(), '1.1.4') < 0) {
                $this->addWidget();
                $this->createWebsiteandStore();
                $this->theme();
                $this->newWebsite();
            }
            $setup->endSetup();
        }

        public function addWidget(): void
        {
            try {
                $content = '{{widget type="Dev\ProductComments\Block\Widget\Products" products_count="10"}}';
                $page = $this->pageRepository->getById(2);
                $page->setContent($content);
                $this->page->save($page);
            } catch (Exception $e) {
            }
        }

        public function getRootCategoryId()
        {
            $store = 1;
            return $this->storeManagerInterface->getStore($store)->getRootCategoryId();
        }

        public function createWebsiteandStore(): void
        {
            $website = $this->websiteFactory->create();
            $website->setName('georgian_website')->setCode('devall');
            try {
                $this->website->save($website);
            } catch (AlreadyExistsException $e) {
            }
            $websiteId = $website->getWebsiteId();

            $group = $this->groupFactory->create();
            $group->setWebsiteId($websiteId);
            $group->setName('georgian website store-group');
            $group->setCode('georgian_store-group');
            $group->setRootCategoryId($this->getRootCategoryId());
            try {
                $this->group->save($group);
            } catch (AlreadyExistsException $e) {
            }
            $groupId=$group->getGroupId();

            $store=$this->storeFactory->create();
            $store->setWebsiteId($websiteId);
            $store->setName('ge');
            $store->setCode('devall_ge');
            $store->setData('is_active', '1');
            $store->setGroupId(($groupId));
            try {
                $this->store->save($store);
            } catch (AlreadyExistsException $e) {
            }
            $this->currencyAndLanguage($websiteId);
        }
        public function currencyAndLanguage() : void
        {
            $configs=[
                [
                    'path'  =>'general/country/default',
                    'value' =>'GE'
                ],
                [
                    'path'  => 'general/country/allow',
                    'value'=>'GE'
                ],
                [
                    'path'  => 'general/locale/timezone',
                    'value'=>'Asia/Tbilisi'
                ],
                [
                    'path'  => 'general/locale/code',
                    'value'=>'ka_GE'
                ],
                [
                    'path'  => 'currency/options/default',
                    'value'=>'GEL'
                ],
                [
                    'path'  => 'currency/options/allow',
                    'value'=>'GEL'
                ],
            ];

            foreach ($configs as $config) {
                $this->resourceConfig->saveConfig($config['path'], $config['value'], 'websites', 2);
            }
        }
        public function newWebsite()
        {
            $webSiteUrl = [
            [
                'path' => 'web/unsecure/base_url',
                'value' => 'http://local.devall.ge/'
            ],
            [
                'path' => 'web/unsecure/base_link_url',
                'value' => 'http://local.devall.ge/'
            ]
            ];
            foreach ($webSiteUrl as $webSite) {
                $this->resourceConfig->saveConfig($webSite['path'], $webSite['value'], 'websites', 2);
            }
        }

        public function theme(): void
        {
            $this->resourceConfig->saveConfig('design/theme/theme_id', 4, 'websites', 2);
        }
    }
}
