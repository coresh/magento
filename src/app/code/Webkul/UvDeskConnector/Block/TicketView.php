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

class TicketView extends \Magento\Framework\View\Element\Template
{
    /**
     * [__construct description]
     * @param \Magento\Framework\View\Element\Template\Context $context       [description]
     * @param \Webkul\UvDeskConnector\Helper\Tickets           $ticketHelper  [description]
     * @param \Webkul\UvDeskConnector\Model\TicketManager      $ticketManager [description]
     * @param array                                            $data          [description]
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Webkul\UvDeskConnector\Helper\Tickets $ticketHelper,
        \Webkul\UvDeskConnector\Model\TicketManager $ticketManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_ticketHelper = $ticketHelper;
        $this->_ticketManager = $ticketManager;
    }

    public function getLoggedInUserDetail()
    {
        $customerDetal = $this->_ticketHelper->getLoggedInUserDetail();
        return $customerDetal;
    }

    public function getTicketThread()
    {
        $ticketId = $this->getRequest()->getParam('id');
        $threads = $this->_ticketManager->getTicketThread($ticketId, null);
        return $threads;
    }

    public function isCustomer($name)
    {
        if ($name == 'customer') {
            return 'customer';
        }
        return '';
    }

    public function getSingleTicketData()
    {
        $ticketIncrementId = $this->getRequest()->getParam('increment_id');
        $ticketData = $this->_ticketManager->getSingleTicketData($ticketIncrementId);
        return $ticketData ;
    }
}
