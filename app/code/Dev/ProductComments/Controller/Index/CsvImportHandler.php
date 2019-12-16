<?php

namespace Dev\ProductComments\Controller\Index;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Data\Form\Filter\Date;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use RuntimeException;

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
     * @var Date
     */
    private $Date;
    /**
     * @var ProductRepositoryInterface
     */
    private $productReposityInterface;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    public function __construct(
        Csv $csvProcessor,
        DirectoryList $directoryList,
        Filesystem $filesystem,
        TimezoneInterface $Date,
        ProductRepositoryInterface $productReposityInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->Date=$Date;
        $this->filesystem = $filesystem;
        $this->directoryList = $directoryList;
        $this->csvProcessor = $csvProcessor;
        $this->productReposityInterface = $productReposityInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function execute(): bool
    {
        $DD = $this->Date->date()->format('d');
        $MM = $this->Date->date()->format('m');
        $YYYY = $this->Date->date()->format('y');

        $fileDirectoryPath = $this->directoryList->getPath(DirectoryList::VAR_DIR) . '/export/product/';

        if (!is_dir($fileDirectoryPath) && !mkdir($fileDirectoryPath, 0777, true) && !is_dir($fileDirectoryPath)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $fileDirectoryPath));
        }

        $fileName = 'product_export_' . $DD . '_' . $MM . '_' . $YYYY . '.csv';
        $filePath = $fileDirectoryPath . '/' . $fileName;
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $products = $this->productReposityInterface
            ->getList($searchCriteria)
            ->getItems();
        foreach ($products as $product) {
            $data[] = [
                'name'=>$product->getName(),
                'type'=>$product->getTypeId(),
                'sku'=>$product->getSku()
            ];
            $this->csvProcessor
                ->setEnclosure('"')
                ->setDelimiter(',')
                ->saveData($filePath, $data);
        }

            return true;
    }
}
