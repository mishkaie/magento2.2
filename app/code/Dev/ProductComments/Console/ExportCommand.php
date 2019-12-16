<?php

namespace Dev\ProductComments\Console;

use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Dev\ProductComments\Controller\Index\CsvImportHandler;
use Magento\Framework\App\State;

class ExportCommand extends Command
{
    /**
     * @var CsvImportHandler
     */
    private $csvImportHandler;
    /**
     * @var State
     */
    protected $appState;

    public function __construct(
        State $appState,
        CsvImportHandler $csvImportHandler
    ) {
        $this->appState = $appState;
        $this->csvImportHandler = $csvImportHandler;
        parent::__construct();
    }
    public function configure():void
    {
        $this->setName('products:export');
        $this->setDescription('Export products in csv file');
        parent::configure();
        try {
            $this->appState->setAreaCode('frontend');
        } catch (LocalizedException $e) {
        }
    }
    public function execute(InputInterface $input, OutputInterface $output)
    {
            return $this->csvImportHandler->execute();
    }
}
