<?php
namespace Dev\ProductComments\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Catalog\Helper\Image;
use Magento\Framework\Pricing\Helper\Data;

class Products extends Template implements BlockInterface
{
    protected $_template = 'widget/products.phtml';
    private $productRepository;
    private $criteriaBuilder;
    private $productImageHelper;
    private $objectManager;

    public function __construct(
        Template\Context $context,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $criteriaBuilder,
        Image $productImageHelper,
        Data $objectManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->productRepository=$productRepository;
        $this->productImageHelper=$productImageHelper;
        $this->criteriaBuilder=$criteriaBuilder;
        $this->objectManager=$objectManager;
    }

    public function getProductCollection($maxProducts)
    {
        $criterium=$this->criteriaBuilder->addFilter('product_comments', 'yes')->create()->setPageSize($maxProducts);
        return $this->productRepository->getList($criterium)->getItems();
    }

    public function getImage($product)
    {
        return $this->productImageHelper->init($product, 'product_base_image')->getUrl();
    }

    public function getPrice($product)
    {
        return $this->objectManager->currency($product, true, false);
    }
}
