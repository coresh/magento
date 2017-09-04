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
 * Webkul UvDeskConnector Landing page Index Controller.
 */
class Index extends Action
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
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Webkul\UvDeskConnector\Model\TicketManager $ticketManager,
        \Webkul\UvDeskConnector\Helper\Tickets $ticketHelper
    ) {
    
        $this->_resultPageFactory = $resultPageFactory;
        $this->_json = $jsonHelper;
        $this->_ticketManager = $ticketManager;
        $this->_ticketHelper = $ticketHelper;
        parent::__construct($context);
    }

    /**
     * UvDeskConnector Landing page.
     *
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
                if ($file['error'] == 1) {
                    $error = 1;
                    break;
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
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if ($error == 1) {
            $this->messageManager->addError(__('Attached file size issue.Please contact admin.'));
            $resultRedirect->setPath(
                'uvdeskcon/ticketlist/index/'
            );
            return $resultRedirect;
        }
        $response = $this->_ticketManager->createTicket($data, $mime_boundary);
        $resultRedirect->setPath(
            'uvdeskcon/ticketlist/index/'
        );
        return $resultRedirect;
    }
}
