<?php
namespace Dev\ProductComments\Controller\Observer;

use Magento\Framework\App\Area;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Event\Observer;
use Magento\Store\Model\Store;

class EmailObserver implements ObserverInterface
{
    protected $transportBuilder;

    public function __construct(
        TransportBuilder $transportBuilder
    ) {
        $this->transportBuilder=$transportBuilder;
    }

    public function execute(Observer $observer)
    {
            $email=$observer->getData('email');
            $comment=$observer->getData('comment');
            $sender = [
                'name' => ('admin'),
                'email' =>('admin@magento.com'),
            ];
            $templateParams = ['comment' => $comment];
            $transport = $this->transportBuilder
                ->setTemplateIdentifier('commentvisibility_email_template')
                ->setTemplateOptions(
                    [
                        'area' => Area::AREA_FRONTEND,
                        'store' => Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars($templateParams)
                ->setFrom($sender)
                ->addTo($email)
                ->getTransport();
        try {
            $transport->sendMessage();
        } catch (\Exception $e) {
        }
        return $this;
    }
}
