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

class GetTickets extends \Magento\Backend\App\Action
{
    /** @var \Magento\Framework\View\Result\PageFactory */    
    protected $_resultPageFactory;

    /** @var \Magento\Framework\Json\Helper\Data */    
    protected $_jsonHelper;

    /** @var \UvDeskConnector\Model\TicketManager */    
    protected $_ticketManager;

    /** @var \Webkul\UvDeskConnector\Helper\Tickets */    
    protected $_ticketsHelper;

   /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */      
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,        
        \Webkul\UvDeskConnector\Model\TicketManager $ticketManager,
        \Webkul\UvDeskConnector\Helper\Tickets $ticketsHelper
    ) 
    {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_json = $jsonHelper;
        $this->_ticketManager = $ticketManager;
        $this->_ticketsHelper = $ticketsHelper;
    }

    public function execute()
    {
        // echo "<pre>";
        // print_r($this->getRequest()->getParams());
        // die;
        $page = $this->checkStatus('pageNo');
        $label = $this->checkStatus('labels');  
        $tab = $this->checkStatus('tab');
        $agent = $this->checkStatus('agent');
        $customer = $this->checkStatus('customer');
        $group = $this->checkStatus('group');
        $team = $this->checkStatus('team');
        $priority = $this->checkStatus('priority');
        $type = $this->checkStatus('type');
        $tag = $this->checkStatus('tag');
        $mailbox =$this->checkStatus('mailbox');  
        // $this->_ticketsHelper->formatParameter($this->getRequest()->getParams());
        // $tab = $this->getRequest()->getParam('tab');
        // $label = $this->getRequest()->getParam('labels');
        // $agent = $this->getRequest()->getParam('agent');
        // $customer = $this->getRequest()->getparam('customer');
        $tickets = $this->_ticketManager->getAllTicketss($page,$label,$tab,$agent,$customer,$group,$team,$priority,$type,$tag,$mailbox);
        //         echo "<pre>";
        // print_r($tickets);
        // die;
        $formatedTickets = $this->_ticketsHelper->formatData($tickets);
        // echo "<pre>";
        // print_r($formatedTickets);
        // die;
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($this->_json->jsonEncode($formatedTickets));          
        // $resultPage = $this->_resultPageFactory->create();
        // $resultPage->getConfig()->getTitle()->prepend(__('Tickets'));
        // return $resultPage;
    }

    public function checkStatus($code){
        $flag = $this->getRequest()->getParam($code);
        if(isset($flag)) {
            return $this->getRequest()->getParam($code);
        } else {
            return null;
        }

    }
    

    /*
     * Check permission via ACL resource
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_UvDeskConnector::tickets_gettickets');
    }
}
