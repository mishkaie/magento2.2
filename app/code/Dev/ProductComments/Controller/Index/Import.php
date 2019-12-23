<?php
namespace Dev\ProductComments\Controller\Index;

use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\Csv;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Filesystem;

class Import
{
    /**
     * @var Product
     */
    private $product;
    /**
     * @var Csv
     */
    private $csv;
    /**
     * @var ProductInterfaceFactory
     */
    private $productFactory;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var StockRegistryInterface
     */
    private $stockRegistry;
    /**
     * @var TimezoneInterface
     */
    private $date;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var File
     */
    private $file;
    public function __construct(
        Product $product,
        Csv $csv,
        ProductInterfaceFactory $productFactory,
        ProductRepositoryInterface $productRepository,
        StockRegistryInterface $stockRegistry,
        TimezoneInterface $date,
        Filesystem $filesystem,
        File $file
    ) {
        $this->product = $product;
        $this->csv = $csv;
        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;
        $this->stockRegistry = $stockRegistry;
        $this->date = $date;
        $this->filesystem = $filesystem;
        $this->file = $file;
    }
    public function execute()
    {
        $DD = $this->date->date()->format('d');
        $MM = $this->date->date()->format('m');
        $YYYY = $this->date->date()->format('Y');
        $fileName = 'var/import/product/product_export_'
            . $DD .'_' . $MM . '_' . $YYYY . '.csv';
        if (!isset($fileName)) {
            throw new LocalizedException(__('Cann`t upload file.'));
        }
        $csvData = $this->csv->getData($fileName);
        foreach ($csvData as $row => $data) {
            if ($row > 0) {
                $product = $this->productFactory->create();
                $product->setSku($data[2]);
                $product->setTypeId($data[1]);
                $product->setName($data[0]);
                $product->setVisibility(4);
                $product->setPrice(1);
                $product->setAttributeSetId(4);
                $product->setStatus(1);
                $product = $this->productRepository->save($product);
                $stockItem = $this->stockRegistry->getStockItemBySku($product->getSku());
                $stockItem->setIsInStock(1);
                $stockItem->setQty(1);
                $this->stockRegistry->updateStockItemBySku($product->getSku(), $stockItem);
                if ($product) {
                    try {
                        $this->file->deleteFile($fileName);
                    } catch (FileSystemException $e) {
                    }
                }
            }
        }
    }
}
