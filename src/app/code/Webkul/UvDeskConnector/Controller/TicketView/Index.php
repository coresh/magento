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
namespace Webkul\UvDeskConnector\Controller\TicketView;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use  Magento\Framework\Controller\ResultFactory;

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
     * @param \Webkul\UvDeskConnector\Model\TicketManager $ticketManager
     * @param \Webkul\UvDeskConnector\Helper\Tickets      $ticketHelper
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Webkul\UvDeskConnector\Model\TicketManager $ticketManager,
        \Webkul\UvDeskConnector\Helper\Tickets $ticketHelper
    ) 
    {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_ticketManager = $ticketManager;
        $this->_ticketHelper = $ticketHelper;
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
        // $resultPage->getConfig()->getTitle()->set(__('UVdesk Add On'));
        // $resultPage->getConfig()->getTitle()->prepend(__('Tickets'));
        $post = $this->getRequest()->getParams();
        $ticketId = isset($post['ticket_id'])?$post['ticket_id']:null;
        $tickeIncrementId = isset($post['incremet_id'])?$post['incremet_id']:null;
        $reply = isset($post['product']['description'])?$post['product']['description']:null;
        $actAsType = 'customer';    
        if (isset($post['addReply']) &&  $post['addReply'] ==  1 ) {
            $data = ["threadType"=>"reply", "reply"=>$reply, "status"=>"1", "actAsType"=>$actAsType];
            $email = $this->_ticketHelper->getLoggedInUserDetail()['email'];
            if ($email) {
                $data["actAsEmail"]=$email;
            }
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $response = $this->_ticketManager->addReplyToTickett($ticketId, $tickeIncrementId, $data);
            $resultRedirect->setPath(
                'uvdeskcon/ticketview/index/', ['id' => $ticketId,'increment_id'=>$tickeIncrementId]
            );
            return $resultRedirect;
        }
        return $resultPage;
    }
}
