<?php

namespace Dev\ProductComments\Block;

use Magento\Store\Block\Switcher as StoreSwitcher;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Data\Helper\PostHelper as PostHelperAlias;
use Magento\Framework\Url\Helper\Data as UrlHelper;
use Magento\Framework\View\Element\Template\Context as ContextAlias;

class Switcher extends StoreSwitcher
{
    /**
     * @var bool
     */
    protected $_storeInUrl;

    /**
     * @var PostHelperAlias
     */
    protected $_postDataHelper;

    /**
     * @var UrlHelper
     */
    private $urlHelper;

    /**
     * Constructs
     *
     * @param ContextAlias $context
     * @param PostHelperAlias $postDataHelper
     * @param array $data
     * @param UrlHelper $urlHelper
     */
    public function __construct(
        ContextAlias $context,
        PostHelperAlias $postDataHelper,
        array $data = [],
        UrlHelper $urlHelper = null
    ) {
        parent::__construct($context, $postDataHelper, $data, $urlHelper);
        $this->urlHelper = $urlHelper ?: ObjectManager::getInstance()->get(UrlHelper::class);
    }

    /**
     * Get default stores from websites other than currently open.
     *
     * @return array
     */
    public function getStores(): ?array
    {
        $currentGroupId = $this->_storeManager->getGroup()->getId();

        $groups = $this->_storeManager->getGroups();

        $websiteStores = [];

        foreach ($groups as $group) {
            if ($group->getId() !== $currentGroupId) {
                $group->getDefaultStore()->getBaseUrl();
                $websiteStores[] = $group->getDefaultStore();
            }
        }

        return $websiteStores;
    }
}
