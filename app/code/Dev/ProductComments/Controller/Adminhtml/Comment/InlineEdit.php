<?php

namespace Dev\ProductComments\Controller\Adminhtml\Comment;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Dev\ProductComments\Model\Comment;

class InlineEdit extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    private $commentModel;
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param Comment $commentModel
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        Comment $commentModel,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->commentModel = $commentModel;
        $this->jsonFactory = $jsonFactory;
    }


    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $modelid) {
                    $model = $this->commentModel->load($modelid);
                    try {
                        $model->setData(array_merge($model->getData(), $postItems[$modelid]));
                        $model->save();
                    } catch (\Exception $e) {
                        $messages[] = "[Mymodel ID: {$modelid}]  {$e->getMessage()}";
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}