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

namespace Webkul\UvDeskConnector\Controller\Adminhtml\Tickets;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class DownloadAttachment extends \Magento\Backend\App\Action
{
    /** @var \Magento\Framework\View\Result\PageFactory */    
    protected $_resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context              $context
     * @param \Magento\Framework\Controller\Result\RawFactory  $resultRawFactory
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Webkul\UvDeskConnector\Model\TicketManager      $ticketManager
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Webkul\UvDeskConnector\Model\TicketManager $ticketManager
    ) 
    {
        parent::__construct($context);
        $this->resultRawFactory      = $resultRawFactory;
        $this->fileFactory           = $fileFactory;
        $this->_ticketManager        = $ticketManager;
    }

    public function execute()
    {
      $attachmenId = $this->getRequest()->getParam('attachment_id');
      $name = $this->getRequest()->getParam('name');
      $file = $this->_ticketManager->downloadAttachment($attachmenId);
      header('Content-Disposition: attachment; filename="'.$name.'"');
      header('Content-Type: '.$file['info']['content_type']); 
      header('Content-Length: ' . strlen($file['response']));
      header('Connection: close');
      echo $file['response'];
    }

    /*
     * Check permission via ACL resource
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_UvDeskConnector::tickets_index');
    }
}
