<?php

namespace Dev\ProductComments\Controller\Index;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use RuntimeException;
use Magento\Framework\Filesystem\Io\File;

class CsvImportHandler
{
    /**
     * @var DirectoryList
     */
    private $directoryList;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var Csv
     */
    private $csvProcessor;
    /**
     * @var TimezoneInterface
     */
    private $date;
    /**
     * @var ProductRepositoryInterface
     */
    private $productReposityInterface;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var File
     */
    private $file;

    public function __construct(
        Csv $csvProcessor,
        DirectoryList $directoryList,
        Filesystem $filesystem,
        ProductRepositoryInterface $productReposityInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        TimezoneInterface $date,
        File $file
    ) {
    
        $this->date = $date;
        $this->filesystem = $filesystem;
        $this->directoryList = $directoryList;
        $this->csvProcessor = $csvProcessor;
        $this->productReposityInterface = $productReposityInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->file = $file;
    }

    public function execute(): bool
    {
        $DD = $this->date->date()->format('d');
        $MM = $this->date->date()->format('m');
        $YYYY = $this->date->date()->format('Y');

        $fileDirectoryPath = $this->directoryList->getPath(DirectoryList::VAR_DIR) . '/export/product/';
        if (!is_dir($fileDirectoryPath) && !mkdir($fileDirectoryPath, 0777, true) && !is_dir($fileDirectoryPath)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $fileDirectoryPath));
        }
        $fileName = 'product_export_' . $DD . '_' . $MM . '_' . $YYYY . '.csv';
        $filePath = $fileDirectoryPath . $fileName;
        $data = [];
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $products = $this->productReposityInterface
            ->getList($searchCriteria)
            ->getItems();
        $data[] = ['name', 'type', 'sku'];
        foreach ($products as $product) {
            $data[] = [
                'name' => $product->getName(),
                'type' => $product->getTypeId(),
                'sku' => $product->getSku()
            ];
            $this->csvProcessor
                ->setEnclosure('"')
                ->setDelimiter(',')
                ->saveData($filePath, $data);
        }
        copy(
            'var/export/product/'.$fileName,
            'var/import/product/'.$fileName
        );
        return true;
    }
}
