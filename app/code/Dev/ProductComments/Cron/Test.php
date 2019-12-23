<?php
namespace Dev\ProductComments\Cron;

use Dev\ProductComments\Controller\Index\Import;

class Test
{

    /**
     * @var Import
     */
    private $import;

    public function __construct(
        Import $import
    ) {
    
        $this->import = $import;
    }

    public function execute()
    {
            $this->import->execute();
    }
}
