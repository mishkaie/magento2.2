<?php

namespace Dev\ProductComments\Model\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Comments extends AbstractSource
{

    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ["label" => ("Yes"), "value" => "yes"],
                ["label" => ("No"), "value" => "no"]
            ];
        }
        return $this->_options;
    }
}
