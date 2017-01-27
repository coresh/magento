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
namespace Webkul\UvDeskConnector\Controller\TicketList;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

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
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Json\Helper\Data $jsonHelper,     
        \Webkul\UvDeskConnector\Model\TicketManager $ticketManager,
        \Webkul\UvDeskConnector\Helper\Tickets $ticketsHelper
    ) 
    {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_json = $jsonHelper;
        $this->_customerSession = $customerSession;
        $this->_ticketManager = $ticketManager;
        $this->_ticketsHelper = $ticketsHelper;
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
        if (isset($post['pageNo']) && isset($post['isAjax'])) {
            $customerUvdeskId = $this->_customerSession->getCustomerUvdeskId();
            $tickets = $this->_ticketManager->getAllTicketss($post['pageNo'],null,null,null,$customerUvdeskId);
            $formatedTickets = $this->_ticketsHelper->formatData($tickets);
            $this->getResponse()->setHeader('Content-type', 'application/json');
            $this->getResponse()->setBody($this->_json->jsonEncode($formatedTickets));  

        } else {
            // $resultPage->getConfig()->getTitle()->set(__('UVdesk Add On'));
            return $resultPage;
        }
    }
}
