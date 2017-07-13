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
     * @param \Magento\Customer\Model\Session             $customerSession
     * @param \Webkul\UvDeskConnector\Model\TicketManager $ticketManager
     * @param \Webkul\UvDeskConnector\Model\TicketManager $ticketManager
     * @param \Webkul\UvDeskConnector\Helper\Tickets      $ticketsHelper
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
        $post = $this->getRequest()->getParams();
        if (isset($post['pageNo']) && isset($post['isAjax'])) {
            $customerUvdeskId = $this->_customerSession->getCustomerUvdeskId();
            $tickets = $this->_ticketManager->getAllTicketss($post['pageNo'], null, null, null, $customerUvdeskId);
            $formatedTickets = $this->_ticketsHelper->formatData($tickets);
            $this->getResponse()->setHeader('Content-type', 'application/json');
            $this->getResponse()->setBody($this->_json->jsonEncode($formatedTickets));  

        } else {
            // $resultPage->getConfig()->getTitle()->set(__('UVdesk Add On'));
            return $resultPage;
        }
    }
}
