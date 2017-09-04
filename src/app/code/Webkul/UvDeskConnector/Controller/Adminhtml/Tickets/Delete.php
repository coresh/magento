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

class Delete extends \Magento\Backend\App\Action
{
    /** @var \Magento\Framework\View\Result\PageFactory */
    protected $_resultPageFactory;

    /** @var \Webkul\UvDeskConnector\Helper\Data */
    protected $_helperData;

   /**
    * @param \Magento\Backend\App\Action\Context $context
    * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
    * @param \Webkul\UvDeskConnector\Helper\Data $helperData
    */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Webkul\UvDeskConnector\Helper\Data $helperData,
        \Webkul\UvDeskConnector\Model\TicketManager $ticketManager
    ) {
    
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_helperData = $helperData;
        $this->_ticketManager = $ticketManager;
    }

    public function execute()
    {
        $successCount = 0;
        $errorCount = 0;
        $post = $this->getRequest()->getParams();
        if (isset($post['id']) && !empty($post['id'])) {
            // foreach ($post['id'] as $ticketId) {
                $response = $this->_ticketManager->deleteTicket($post['id']);
            if ($response['response']) {
                $successCount++;
            } else {
                $errorCount++;
            }
            // }/
            if ($successCount) {
                $this->messageManager->addSuccess(__("Success ! Ticket(s) removed successfully."));
            }
            if ($errorCount) {
                $this->messageManager->addError(__("Error ! %1 Tickets not removed successfully."));
            }
        }
    }

    /*
     * Check permission via ACL resource
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_UvDeskConnector::tickets');
    }
}
