<?php
/**
* Webkul Software.
*
* @category Webkul
* @package Webkul_UvDeskConnector
* @author Webkul
* @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
* @license https://store.webkul.com/license.html
*/

namespace Webkul\UvDeskConnector\Controller\Adminhtml\Tickets;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class DownloadAttachment extends \Magento\Backend\App\Action
{
    /** @var \Magento\Framework\View\Result\PageFactory */    
    protected $_resultPageFactory;

   /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
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
      // echo "<pre>";
      // print_r($file);
      // die;

      header('Content-Disposition: attachment; filename="'.$name.'"');
      header('Content-Type: '.$file['info']['content_type']); # Don't use application/force-download - it's not a real MIME type, and the Content-Disposition header is sufficient
      header('Content-Length: ' . strlen($file['response']));
      header('Connection: close');
      echo $file['response'];
        //          $this->getResponse ()   ->setHttpResponseCode ( 200 )
        //             ->setHeader ( 'Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true )
        //              ->setHeader ( 'Pragma', 'public', true )
        //             ->setHeader ( 'Content-type', 'application/force-download' )
        //             ->setHeader ( 'Content-Length', 696 )
        //             ->setHeader ('Content-Disposition', 'attachment' . '; filename=' . basename('http://testingnew.voipkul.com/en/api/ticket/attachment/18077.json') );
        // $this->getResponse ()->clearBody ();
        // $this->getResponse ()->sendHeaders ();
        // readfile ( 'http://testingnew.voipkul.com/en/api/ticket/attachment/18077.json' );
        // exit;
        // $resultPage = $this->_resultPageFactory->create();
        // $resultPage->getConfig()->getTitle()->prepend(__('Tickets'));
        // return $resultPage;
    }

    /*
     * Check permission via ACL resource
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_UvDeskConnector::tickets_index');
    }
}
