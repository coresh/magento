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
namespace Webkul\UvDeskConnector\Controller\TicketView;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;

use Magento\Framework\App\RequestInterface;

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
     * @param Context                                     $context
     * @param PageFactory                                 $resultPageFactory
     * @param \Webkul\UvDeskConnector\Model\TicketManager $ticketManager
     * @param \Webkul\UvDeskConnector\Helper\Tickets      $ticketHelper
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Webkul\UvDeskConnector\Model\TicketManager $ticketManager,
        \Webkul\UvDeskConnector\Helper\Tickets $ticketHelper
    ) {
    
        $this->_resultPageFactory = $resultPageFactory;
        $this->_ticketManager = $ticketManager;
        $this->_ticketHelper = $ticketHelper;
        parent::__construct($context);
    }
    
    /**
     * Check customer is logged in or not ?
     *
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function dispatch(RequestInterface $request)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->get('Magento\Customer\Model\Session');
        if (!$customerSession->authenticate()) {
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
        }
        return parent::dispatch($request);
    }

    /**
     * UvDeskConnector Landing page.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        // $resultPage->getConfig()->getTitle()->set(__('UVdesk Add On'));
        // $resultPage->getConfig()->getTitle()->prepend(__('Tickets'));
        $post = $this->getRequest()->getParams();
        $attachments = $this->getRequest()->getFiles();
        $error = 0;
        $ticketId = isset($post['ticket_id'])?$post['ticket_id']:null;
        $tickeIncrementId = isset($post['incremet_id'])?$post['incremet_id']:null;
        $reply = isset($post['product']['description'])?$post['product']['description']:null;
        $email = $this->_ticketHelper->getLoggedInUserDetail()['email'];
        // $actAsType = 'customer';
        if (isset($post['addReply']) && $post['addReply'] ==  1) {
            $lineEnd = "\r\n";
            $mime_boundary = md5(time());
            $data = '--' . $mime_boundary . $lineEnd;
            $data .= 'Content-Disposition: form-data; name="reply"' . $lineEnd . $lineEnd;
            $data .= $reply . $lineEnd;
            $data .= '--' . $mime_boundary . $lineEnd;
            $data .= 'Content-Disposition: form-data; name="threadType"' . $lineEnd . $lineEnd;
            $data .= "reply" . $lineEnd;
            $data .= '--' . $mime_boundary . $lineEnd;
            $data .= 'Content-Disposition: form-data; name="status"' . $lineEnd . $lineEnd;
            $data .= "1". $lineEnd;
            $data .= '--' . $mime_boundary . $lineEnd;
            // attachements
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
            $data .= 'Content-Disposition: form-data; name="actAsType"' . $lineEnd . $lineEnd;
            $data .= 'customer'. $lineEnd;
            $data .= '--' . $mime_boundary . $lineEnd;
            if ($email) {
                $data .= 'Content-Disposition: form-data; name="actAsEmail"' . $lineEnd . $lineEnd;
                $data .= $email . $lineEnd;
                $data .= '--' . $mime_boundary . $lineEnd;
            }
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            if ($error == 1) {
                $this->messageManager->addError(__('Attached file size issue.Please contact admin.'));
                $resultRedirect->setPath(
                    'uvdeskcon/ticketview/index/',
                    ['id' => $ticketId,'increment_id'=>$tickeIncrementId]
                );
                return $resultRedirect;
            }
            $response = $this->_ticketManager->addReplyToTicket($ticketId, $tickeIncrementId, $data, $mime_boundary);
            $resultRedirect->setPath(
                'uvdeskcon/ticketview/index/',
                ['id' => $ticketId,'increment_id'=>$tickeIncrementId]
            );
            return $resultRedirect;
        }
        return $resultPage;
    }
}
