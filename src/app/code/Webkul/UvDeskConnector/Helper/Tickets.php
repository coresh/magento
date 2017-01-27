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
     * @param Magento\Framework\App\Helper\Context       $context
     * @param \Magento\Framework\Json\Helper\Data        $jsonHelper
     * @param \Magento\Config\Model\ResourceModel\Config $resourceConfig
     * @param Connection                                 $connection
     * @param Logger                                     $logger
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Config\Model\ResourceModel\Config $resourceConfig,
        \Magento\Customer\Model\Session $customerSession
    ) 
    {
    
        $this->_json = $jsonHelper;
        $this->_resourceConfig = $resourceConfig;
        $this->_customerSession = $customerSession;
        parent::__construct($context);
    }

    public function formatData($tickets=[]) 
    {
        $paginationData = [];
        $ticketData = [];
        $tabData = [];
        $data = [];
        $tickets = json_decode(json_encode($tickets),true);
        if(!empty($tickets['tickets'])){
            foreach($tickets['tickets'] as $ticket) {
                    $temp['priority'] = $ticket['priority']['name'];
                    $temp['priority_color'] = $ticket['priority']['color'];
                    $temp['incrementId'] = $ticket['incrementId'];
                    $temp['id'] = $ticket['id'];
                    $temp['name'] = $ticket['customer']['name'];
                    $temp['subject'] = $ticket['subject'];
                    $temp['creation_date'] = $ticket['formatedCreatedAt'];
                    $temp['replies'] = $ticket['totalThreads'];
                    $temp['agent'] = $ticket['agent']['name'];
                    $ticketData[] = $temp;
                    $temp = [];
            }
        }
        if(!empty($tickets['status'])){
            foreach($tickets['status'] as $status) {
                    $temp['tab_name'] = $status['name'];
                    $temp['tab_id'] = $status['id'];
                    $temp['tab_count'] = $tickets['tabs'][$status['sortOrder']];
                    $tabData[] = $temp;
                    $temp = [];
            }
        }
        if(!empty($tickets['pagination'])){
            foreach($tickets['pagination']['pagesInRange'] as $page) {
                if($tickets['pagination']['pageCount']>1){
                    $temp['page_no'] = $page;
                    $paginationData[] = $temp;
                    $temp = [];
                }
            }
        }        
        $data['ticket_data'] = $ticketData;
        $data['tab_data'] = $tabData;
        $data['pagination_data'] = $paginationData;
        return $data ;
    }

    public function formatParameter($parameter){
        echo "<pre>";
        print_r($parameter);
        die;
    }

    public function getLoggedInUserDetail(){
        $customerDetal = [];
        $customerDetal['email'] = $this->_customerSession->getCustomer()->getEmail();
        $customerDetal['name'] = $this->_customerSession->getCustomer()->getName();
        return $customerDetal;
    } 

}