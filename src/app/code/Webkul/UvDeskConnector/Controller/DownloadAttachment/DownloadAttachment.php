<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_UvDeskConnector
 * @author    Webkul Software Private Limited
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\UvDeskConnector\Controller\DownloadAttachment;

use Webkul\UvDeskConnector\Controller\AbstractController;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * DownloadAttachment class
 */
class DownloadAttachment extends AbstractController
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
    
    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $_resultRawFactory;
    
    /**
     * @var \Webkul\UvDeskConnector\Model\TicketManagerCustomer
     */
    protected $_ticketManagerCustomer;

    /**
     * __construct function
     *
     * @param Context                                             $context
     * @param PageFactory                                         $resultPageFactory
     * @param \Magento\Framework\Controller\Result\RawFactory     $resultRawFactory
     * @param \Webkul\UvDeskConnector\Model\TicketManagerCustomer $ticketManagerCustomer
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Webkul\UvDeskConnector\Model\TicketManagerCustomer $ticketManagerCustomer
    ) {
    
        $this->_resultPageFactory = $resultPageFactory;
        $this->_ticketManagerCustomer = $ticketManagerCustomer;
        $this->_resultRawFactory = $resultRawFactory;
        parent::__construct($context, $resultPageFactory);
    }

    /**
     * execute function
     *
     * @return void
     */
    public function execute()
    {
        $attachmenId = $this->getRequest()->getParam('attachment_id');
        $name = $this->getRequest()->getParam('name');
        $file = $this->_ticketManagerCustomer->downloadAttachment($attachmenId);
        header('Content-Disposition: attachment; filename="'.$name.'"');
        header('Content-Type: '.$file['info']['content_type']);
        header('Content-Length: ' . strlen($file['response']));
        header('Connection: close');
        $resultRaw = $this->_resultRawFactory->create();
        $resultRaw->setContents($file['response']);
        return $resultRaw;
    }
}
