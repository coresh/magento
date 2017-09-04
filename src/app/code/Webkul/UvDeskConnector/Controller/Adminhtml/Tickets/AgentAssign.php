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

class AgentAssign extends \Magento\Backend\App\Action
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
     * @param \Magento\Backend\App\Action\Context         $context
     * @param \Magento\Framework\View\Result\PageFactory  $resultPageFactory
     * @param \Magento\Framework\Json\Helper\Data         $jsonHelper
     * @param \Webkul\UvDeskConnector\Model\TicketManager $ticketManager
     * @param \Webkul\UvDeskConnector\Helper\Tickets      $ticketsHelper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Webkul\UvDeskConnector\Model\TicketManager $ticketManager,
        \Webkul\UvDeskConnector\Helper\Tickets $ticketsHelper
    ) {
    
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_json = $jsonHelper;
        $this->_ticketManager = $ticketManager;
        $this->_ticketsHelper = $ticketsHelper;
    }

    public function execute()
    {
        $ticketId = $this->getRequest()->getParam('ticketid');
        $agentId = $this->getRequest()->getParam('agentId');
        $tickets = $this->_ticketManager->assignAgentToTicket($ticketId, $agentId);
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($this->_json->jsonEncode($tickets));
    }
    
    /*
     * Check permission via ACL resource
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_UvDeskConnector::tickets');
    }
}
