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
namespace Webkul\UvDeskConnector\Controller\CreateTicket;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;

/**
 * Index class
 */
class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
    
    /**
     * @var \Webkul\UvDeskConnector\Model\TicketManagerCustomer
     */
    protected $_ticketManagerCustomer;
    
    /**
     * @var \Webkul\UvDeskConnector\Helper\Tickets
     */
    protected $_ticketHelper;

    /**
     * __construct function
     *
     * @param Context                                             $context
     * @param PageFactory                                         $resultPageFactory
     * @param \Webkul\UvDeskConnector\Model\TicketManagerCustomer $ticketManagerCustomer
     * @param \Webkul\UvDeskConnector\Helper\Tickets              $ticketHelper
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Webkul\UvDeskConnector\Model\TicketManagerCustomer $ticketManagerCustomer,
        \Webkul\UvDeskConnector\Helper\Tickets $ticketHelper
    ) {
    
        $this->_resultPageFactory = $resultPageFactory;
        $this->_ticketManagerCustomer = $ticketManagerCustomer;
        $this->_ticketHelper = $ticketHelper;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        $post = $this->getRequest()->getParams();
        $attachments = $this->getRequest()->getFiles();
        $error = 0;
        $customerDetail = $this->_ticketHelper->getLoggedInUserDetail();
        $lineEnd = "\r\n";
        $mime_boundary = md5(time());
        $data = '--' . $mime_boundary . $lineEnd;
        $data .= 'Content-Disposition: form-data; name="type"' . $lineEnd . $lineEnd;
        $data .= $post['type'] . $lineEnd;
        $data .= '--' . $mime_boundary . $lineEnd;
        $data .= 'Content-Disposition: form-data; name="from"' . $lineEnd . $lineEnd;
        $data .= $customerDetail['email'] . $lineEnd;
        $data .= '--' . $mime_boundary . $lineEnd;
        $data .= 'Content-Disposition: form-data; name="name"' . $lineEnd . $lineEnd;
        $data .= $customerDetail['name'] . $lineEnd;
        $data .= '--' . $mime_boundary . $lineEnd;
        $data .= 'Content-Disposition: form-data; name="reply"' . $lineEnd . $lineEnd;
        $data .= $post['message'] . $lineEnd;
        $data .= '--' . $mime_boundary . $lineEnd;
        $data .= 'Content-Disposition: form-data; name="subject"' . $lineEnd . $lineEnd;
        $data .= $post['subject'] . $lineEnd;
        $data .= '--' . $mime_boundary . $lineEnd;
        if (isset($attachments['attachment']) && $attachments['attachment'][0]['error'] != 4) {
            foreach ($attachments['attachment'] as $key => $file) {
                if ($file['error'] != 4) {
                    if ($file['error'] == 1) {
                        $error = 1;
                        break;
                    }
                    if ($file['error'] == 4) {
                        continue;
                    }
                    $fileType = $file['type'];
                    $fileName =  $file['name'];
                    $fileTmpName =  $file['tmp_name'];
                    $data .= 'Content-Disposition: form-data; name="attachments[]"; filename="' . addslashes($fileName) . '"' . $lineEnd;
                    $data .= "Content-Type: $fileType" . $lineEnd . $lineEnd;
                    // $data .= "Content-Length:" . filesize($fileTmpName).$lineEnd . $lineEnd;
                    $data .= file_get_contents($fileTmpName) . $lineEnd;
                    $data .= '--' . $mime_boundary . $lineEnd;
                }
            }
        }
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if ($error == 1) {
            $this->messageManager->addError(__('Attached file size issue.Please contact administration.'));
            $resultRedirect->setPath(
                'uvdeskcon/ticketlist/index/'
            );
            return $resultRedirect;
        }
        $response = $this->_ticketManagerCustomer->createTicket($data, $mime_boundary);
        $resultRedirect->setPath(
            'uvdeskcon/ticketlist/index/'
        );
        return $resultRedirect;
    }
}
