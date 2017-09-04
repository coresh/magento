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

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Webkul UvDeskConnector Landing page Index Controller.
 */
class DownloadAttachment extends Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Webkul\UvDeskConnector\Model\TicketManager $ticketManager
    ) {
    
        $this->_resultPageFactory = $resultPageFactory;
        $this->_ticketManager        = $ticketManager;
        $this->resultRawFactory      = $resultRawFactory;
        parent::__construct($context);
    }

    /**
     * UvDeskConnector Landing page.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $attachmenId = $this->getRequest()->getParam('attachment_id');
        $name = $this->getRequest()->getParam('name');
        $file = $this->_ticketManager->downloadAttachment($attachmenId);
        header('Content-Disposition: attachment; filename="'.$name.'"');
        header('Content-Type: '.$file['info']['content_type']);
        header('Content-Length: ' . strlen($file['response']));
        header('Connection: close');
        $resultRaw = $this->resultRawFactory->create();
        $resultRaw->setContents($file['response']); //set content for download file here
        return $resultRaw;
    }
}
