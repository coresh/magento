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

namespace Webkul\UvDeskConnector\Block;

class AllTickets extends \Magento\Framework\View\Element\Template
{
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Webkul\UvDeskConnector\Helper\Tickets           $ticketHelper
     * @param \Webkul\UvDeskConnector\Model\TicketManager      $ticketManager
     * @param \Magento\Customer\Model\Session                  $customerSession
     * @param array                                            $data
     */
	public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Webkul\UvDeskConnector\Helper\Tickets $ticketHelper,
        \Webkul\UvDeskConnector\Model\TicketManager $ticketManager,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_ticketHelper = $ticketHelper;
        $this->_ticketManager = $ticketManager;
        $this->_customerSession = $customerSession;
    }

    public function getLoggedInUserDetail(){
        $customerDetal = $this->_ticketHelper->getLoggedInUserDetail();
        return $customerDetal;

    }

    public function getTicketsAccToCustomer()
    {
        $customerUvdeskId = $this->_customerSession->getCustomerUvdeskId();
        $pageNo = $this->getRequest()->getParam('pageNo');
        if(!isset($customerUvdeskId)){
            $customerUvdeskId = 0;
        }        
        if(!isset($pageNo)){
            $pageNo = null;
        }
        $tickets = $this->_ticketManager->getAllTicketss($pageNo,null,null,null,$customerUvdeskId,null,null,null,null,null,null);
        return $this->_ticketHelper->formatData($tickets);   
    }

    public function getTicketsTypes()
    {
        $tickets = $this->_ticketManager->getTicketTypes();
        return json_decode($tickets,true);   
    }
}