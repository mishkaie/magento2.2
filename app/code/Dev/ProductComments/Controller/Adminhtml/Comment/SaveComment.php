<?php
namespace Dev\ProductComments\Controller\Adminhtml\Comment;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Dev\ProductComments\Model\Comment;
use Dev\ProductComments\Model\ResourceModel\Comment as ResourceComment;
use Magento\Framework\App\Request\DataPersistorInterface;

class SaveComment extends Action
{
    private $commentModel;
    private $resourceComment;
    private $dataPersistor;
    public function __construct(
        Context $context,
        Comment $commentModel,
        ResourceComment $resourceComment,
        DataPersistorInterface $dataPersistor
    ) {
    
        parent::__construct($context);
        $this->commentModel = $commentModel;
        $this->resourceComment = $resourceComment;
        $this->dataPersistor = $dataPersistor;
    }
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $id = $this->getRequest()->getParam('comment_id');
            $model = $this->commentModel->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Comment no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            $model->setData($data);
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the comment.'));
                $this->dataPersistor->clear('product_comments');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['comment_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the comment.'));
            }
            $this->dataPersistor->set('product_comments', $data);
            return $resultRedirect->setPath('*/*/add', ['comment_id' => $this->getRequest()->getParam('comment_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
