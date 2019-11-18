<?php
namespace Dev\ProductComments\Block\Adminhtml\Comment\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveAndContinueButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData():array
    {
        return [
            'label' => __('Save And Continue '),
            'class' => 'save',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'saveAndContinue']]
            ],
            'sort_order' => 90,
        ];
    }
}
