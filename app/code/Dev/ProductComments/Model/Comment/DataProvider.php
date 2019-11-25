<?php
namespace Dev\ProductComments\Model\Comment;

use Dev\ProductComments\Model\ResourceModel\Comment\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Backend\Model\Auth\Session;

class DataProvider extends AbstractDataProvider
{
    private $loadedData;
    protected $collection;
    protected $session;
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $contactCollectionFactory,
        Session $session,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $contactCollectionFactory->create();
        $this->session = $session;
    }
    public function getData()
    {
        $items = $this->collection->getItems();
        foreach ($items as $comment) {
            $this->loadedData[$comment->getId()] = $comment->getData();
        }
        if (!count($items)) {
            $email = $this->session->getUser()->getEmail();
            $comment = $this->collection->getNewEmptyItem();
            $comment->setData('email', $email);
            $comment->setData('hide_field', true);
            $this->loadedData[$comment->getId()] = $comment->getData();
        }
        return $this->loadedData;
    }
}
