<?php
namespace Dev\ProductComments\Block;

use Magento\Framework\View\Element\Template;

class Example extends Template
{
    public function getContent() : string
    {
        return 'Welcome in developers alliance! We are happy to have you on board!';
    }
}