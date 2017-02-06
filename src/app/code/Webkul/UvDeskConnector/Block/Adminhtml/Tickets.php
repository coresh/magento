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

namespace Webkul\UvDeskConnector\Block\Adminhtml;

class Tickets extends \Magento\Backend\Block\Template
{
    /**
     * @var \Magento\Catalog\Block\Adminhtml\Category\Tab\Product
     */
    protected $blockGrid;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * AssignProducts constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Webkul\UvDeskConnector\Model\TicketManager $ticketManager,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    ) {
        $this->_ticketManager = $ticketManager;
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $data);
    }

    public function getWysiwygConfig()
    {
        $config = $this->_wysiwygConfig->getConfig();
        $config = json_encode($config->getData());
        // return $config;
    }

    public function getAllTickets()
    {
        $page = 1;
        $flag = $this->getRequest()->getParam('labels');
        if(isset($flag)){
            $page = $this->getRequest()->getParam('labels');
        }
        $response = null;
        $response = $this->_ticketManager->getAllTickets($page);
        return json_decode(json_encode($response),true);
    }

    public function getFilterDataFor($filterType){
        $response = $this->_ticketManager->getFilterDataFor($filterType);
        return $response;
    }  

    public function labelParamater(){
        return $this->getRequest()->getParam('labels');
    }
        public function getTicketThread()
    {
        $ticketId = $this->getRequest()->getParam('id');
        $threads = $this->_ticketManager->getTicketThread($ticketId);
        return $threads;   
    }

    public function getSingleTicketData()
    {
        $ticketIncrementId = $this->getRequest()->getParam('increment_id');
        $ticketData = $this->_ticketManager->getSingleTicketData($ticketIncrementId);
        return $ticketData ;
    }
}