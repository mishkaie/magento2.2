<?php
namespace Dev\ProductComments\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Dev\ProductComments\Model\CommentFactory;

class Comments extends Template
{
    private $registry;
    private $commentFactory;
    public function __construct(
        Context $context,
        Registry $registry,
        CommentFactory $commentFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->commentFactory = $commentFactory;
    }
    public function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }
    public function getCommentCollection($productId)
    {
        $comment = $this->commentFactory->create();
        $collection = $comment->getCollection()
            ->addFieldToFilter('product_id', $productId)
            ->addFieldToFilter('status', 'Approved')
            ->setOrder('created_at', 'desc')
            ->getItems();
        return $collection;
    }
}
