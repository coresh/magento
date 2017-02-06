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
use  Magento\Framework\Controller\ResultFactory;

class TicketThread extends \Magento\Backend\App\Action
{
    /** @var \Magento\Framework\View\Result\PageFactory */    
    protected $_resultPageFactory;

   /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */      
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Webkul\UvDeskConnector\Model\TicketManager $ticketManager
    ) 
    {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_ticketManager = $ticketManager;
    }

    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();

        $resultPage->getConfig()->getTitle()->prepend(__('Tickets'));
        $post = $this->getRequest()->getParams();
        $ticketId = isset($post['ticket_id'])?$post['ticket_id']:null;
        $tickeIncrementId = isset($post['incremet_id'])?$post['incremet_id']:null;
        $reply = isset($post['product']['description'])?$post['product']['description']:null;
        // $actAsType = 'customer'; 
        if (isset($post['addReply']) &&  $post['addReply'] ==  1 ) {
            $data = ["threadType"=>"reply", "reply"=>$reply, "status"=>"1"];
            // $email = $this->_ticketHelper->getLoggedInUserDetail()['email'];
            // if ($email) {
            //     $data["actAsEmail"]=$email;
            // }
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $response = $this->_ticketManager->addReplyToTicket($ticketId, $tickeIncrementId, $data);
            $resultRedirect->setPath(
                'uvdeskcon/tickets/ticketthread/', ['id' => $ticketId,'increment_id'=>$tickeIncrementId]
            );
            return $resultRedirect;
        }

        return $resultPage;
    }

    /*
     * Check permission via ACL resource
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_UvDeskConnector::tickets_index');
    }
}