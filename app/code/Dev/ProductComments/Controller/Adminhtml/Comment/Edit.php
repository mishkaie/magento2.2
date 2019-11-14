<?php


namespace Dev\ProductComments\Controller\Adminhtml\Comment;

use Magento\Backend\App\Action\Context;
use Dev\ProductComments\Controller\Adminhtml\Comment as CommentController;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Dev\ProductComments\Model\Comment;

class Edit extends CommentController
{
    protected $resultPageFactory;
    private $commentModel;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param Comment $commentModel
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        Comment $commentModel
    ) {
        parent::__construct($context, $coreRegistry);
        $this->resultPageFactory = $resultPageFactory;
        $this->commentModel=$commentModel;
    }



    public function execute()
    {
    
        $id = $this->getRequest()->getParam('comment_id');
        $model = $this->commentModel;

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This comment no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('product_comments', $model);

        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Comment') : __('New Comment'),
            $id ? __('Edit Comment') : __('New Comment')
        );
        $resultPage->getConfig()
            ->getTitle()
            ->prepend($model->getId()? __('Edit Comment %1', $model->getId()) : __('New Comment'));
        return $resultPage;
    }
}
