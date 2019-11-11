<?php
namespace Dev\ProductComments\Ui\Component\Listing\Column;

use Magento\Framework\Option\ArrayInterface;
use Dev\ProductComments\Block\Widget\Products as ProductCollection;

class Products implements ArrayInterface
{
    protected $options;
    protected $productCollection;
    public function __construct(ProductCollection $productCollection)
    {
        $this->productCollection = $productCollection;
    }
    public function toOptionArray()
    {
        $result = [];
        $products = $this->productCollection->getProductCollection(10);
        foreach ($products as $product) {
            $result[] = ['value' => $product->getId(), 'label' => $product->getName()];
        }
        return $result;
    }
}
