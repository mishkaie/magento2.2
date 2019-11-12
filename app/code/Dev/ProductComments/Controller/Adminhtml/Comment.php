<?php
namespace Dev\ProductComments\Controller\Adminhtml;

use Magento\Framework\App\Action\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Registry;

abstract class Comment extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Dev_ProductComments::top_level';


    protected $_coreRegistry;

    /**
     * @param Context $context
     * @param Registry $_coreRegistry
     */
    public function __construct(
        Context $context,
        Registry $_coreRegistry
    ) {
        $this->_coreRegistry = $_coreRegistry;
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @param $resultPage
     * @return Page
     */

    public function initPage($resultPage)
    {
    /** @var Page $resultPage */
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
        ->addBreadcrumb(__('Dev'), __('Dev'))
        ->addBreadcrumb(
            __('Product Comment'),
            __('Product Comment')
        );
        return $resultPage;
    }
}
