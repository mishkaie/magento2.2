<?php
namespace Dev\ProductComments\Controller\Index;

use Dev\ProductComments\Model\Comment;
use Dev\ProductComments\Model\ResourceModel\Comment as ResourceComment;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Exception;
use Zend_Validate;

class SendComment extends Action
{
/**
 * @var Comment
 */
    private $commentModel;
/**
 * @var ResourceComment
 */
    private $resourceModel;
/**
 * SendComment constructor.
 * @param Context $context
 * @param Comment $commentModel
 * @param ResourceComment $resourceModel
 */
    public function __construct(Context $context, Comment  $commentModel, ResourceComment $resourceModel)
    {
        parent::__construct($context);
        $this->commentModel = $commentModel;
        $this->resourceModel = $resourceModel;
    }
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $email = $this->getRequest()->getParam('email');
        $comment = $this->getRequest()->getParam('comment');
        $productId =$this->getRequest()->getParam('productId');
        try {
            if (!Zend_Validate::is($email, 'NotEmpty')) {
                $this->messageManager->addErrorMessage('Email is necessary');
                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                return $resultRedirect;
            } elseif (!Zend_Validate::is($comment, 'NotEmpty')) {
                $this->messageManager->addErrorMessage('Comment is necessary');
                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                return $resultRedirect;
            } else {
                $this->commentModel
                ->setData('product_id', $productId)
                ->setData('email', $email)
                ->setData('comment', $comment);
                try {
                    $this->resourceModel->save($this->commentModel);
                } catch (Exception $e) {
                }
                $this->messageManager->addSuccessMessage('comment request has been sent');
                $this->_eventManager->dispatch('comment_sent', ['email'=>$email,'comment'=>$comment]);
                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                return $resultRedirect;
            }
        } catch (\Zend_Validate_Exception $e) {
        }
    }
}
