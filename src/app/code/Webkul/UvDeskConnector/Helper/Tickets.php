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

namespace Webkul\UvDeskConnector\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

/**
 * UvDeskConnector tickets helper.
 */
class Tickets extends AbstractHelper
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_json;

    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */    
    protected $_resourceConfig;

    /**
     * @param Magento\Framework\App\Helper\Context        $context
     * @param \Magento\Framework\Json\Helper\Data         $jsonHelper
     * @param \Magento\Config\Model\ResourceModel\Config  $resourceConfig
     * @param Connection                                  $connection
     * @param \Webkul\UvDeskConnector\Model\TicketManager $ticketManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Config\Model\ResourceModel\Config $resourceConfig,
        \Magento\Customer\Model\Session $customerSession,
        \Webkul\UvDeskConnector\Model\TicketManager $ticketManager
    ) 
    {
        $this->_json = $jsonHelper;
        $this->_resourceConfig = $resourceConfig;
        $this->_customerSession = $customerSession;
        $this->_ticketManager = $ticketManager;
        parent::__construct($context);
    }

    /**
     * Return the ticket data after formatting it with proper key value pair.
     *
     * @return Array.
     */
    public function formatData($tickets=[]) 
    {
        $paginationData = [];
        $ticketData = [];
        $ticketThreadData = [];
        $ticketThreadPaginationData = [];
        $tabData = [];
        $data = [];
        $allAgent = $this->_ticketManager->getFilterDataFor('agent');
        $tickets = json_decode(json_encode($tickets), true);
        if (isset($tickets['tickets']) && !empty($tickets['tickets'])) {
            foreach ($tickets['tickets'] as $ticket) {
                    $temp['priority'] = $ticket['priority']['name'];
                    $temp['priority_color'] = $ticket['priority']['color'];
                    $temp['incrementId'] = $ticket['incrementId'];
                    $temp['id'] = $ticket['id'];
                    $temp['name'] = $ticket['customer']['name'];
                    $temp['subject'] = $ticket['subject'];
                    $temp['creation_date'] = $ticket['formatedCreatedAt'];
                    $temp['replies'] = $ticket['totalThreads'];
                    $temp['agent'] = $ticket['agent']['name'];
                    // $allAgent[] = $ticket['agent']['name'];
                    $ticketData[] = $temp;
                    $temp = [];
            }
            // $ticketData['allAgent'] = $allAgent;
        }
        if (isset($tickets['status']) && !empty($tickets['status'])) {
            foreach ($tickets['status'] as $status) {
                    $temp['tab_name'] = $status['name'];
                    $temp['tab_id'] = $status['id'];
                    $temp['tab_count'] = $tickets['tabs'][$status['sortOrder']];
                    $tabData[] = $temp;
                    $temp = [];
            }
        }
        if (isset($tickets['pagination']) && !empty($tickets['pagination'])) {
            foreach ($tickets['pagination']['pagesInRange'] as $page) {
                if ($tickets['pagination']['pageCount']>1) {
                    $temp['page_no'] = $page;
                    $paginationData[] = $temp;
                    $temp = [];
                }
            }
        }
        if (isset($tickets['threads']) && !empty($tickets['threads'])) {
            foreach ($tickets['threads'] as $thread) {
                        $temp['id'] = $thread['id'];
                        $temp['name'] = $thread['user']['detail'][$thread['userType']]['name'];
                        $temp['userSmallThumbNail'] = $thread['user']['smallThumbnail'];
                        $temp['customerDetail'] = $thread['user']['detail'][$thread['userType']]['name']; 
                        $temp['userType'] = $thread['userType'];
                        $temp['reply'] = $thread['reply'];
                        $temp['formatedCreatedAt'] = $thread['formatedCreatedAt'];
                        $ticketThreadData[] = $temp;
                        $temp = [];
            }
            $ticketThreadPaginationData['currentPage'] = $tickets['pagination']['current'];
            $ticketThreadPaginationData['lastPage'] = $tickets['pagination']['last'];
            $ticketThreadPaginationData['next'] = $tickets['pagination']['next'] ?? 0;
            $ticketThreadPaginationData['numItemsPerPage'] = $tickets['pagination']['numItemsPerPage']; 
            $ticketThreadPaginationData['totalCount'] = $tickets['pagination']['totalCount']; 

        }        
        $data['ticket_data'] = $ticketData;
        $data['tab_data'] = $tabData;
        $data['pagination_data'] = $paginationData;
        $data['agent-information'] = $allAgent;
        $data['ticket_thread']['thread'] = $ticketThreadData;
        $data['ticket_thread']['pagination'] = $ticketThreadPaginationData;
        return $data ;
    }

    /**
     * Return the logged in user details.
     *
     * @return Array.
     */
    public function getLoggedInUserDetail()
    {
        $customerDetal = [];
        $customerDetal['entity_id'] = $this->_customerSession->getCustomer()->getEntityId();
        $customerDetal['email'] = $this->_customerSession->getCustomer()->getEmail();
        $customerDetal['name'] = $this->_customerSession->getCustomer()->getName();
        return $customerDetal;
    } 

}