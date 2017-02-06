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
namespace Webkul\UvDeskConnector\Model;

class TicketManager
{ 
    public function __construct(
        \Webkul\UvDeskConnector\Helper\Data $helperData,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Customer\Model\Session $customerSession

    )
    {
        $this->_helperData = $helperData;
        $this->_messageManager = $messageManager;
        $this->_customerSession = $customerSession;
    }

    public function getAllTickets($label)
    {
        $access_token = $this->_helperData->getAccessToken();
        $company_domain = $this->_helperData->getCompanyDomainName();

        // Return  tickets 
        $url = 'http://'.$company_domain.'.voipkul.com/en/api/tickets.json?'.$label;
        $ch = curl_init($url);
        $headers = array(
        'Authorization: Bearer '.$access_token,
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($output, 0, $header_size);
        $response = substr($output, $header_size);
        if ($info['http_code'] == 200) {
            return json_decode($response);
        } elseif ($info['http_code'] == 401) {
            $this->_messageManager->addError(__(json_decode($response, true)['error_description']));
            return json_decode($response);
        } elseif ($info['http_code'] == 500 || $info['http_code'] == 0) {
            $this->_messageManager->addError(__('Invalid credentials !'));
            return ['error'=>'true'];
        } 
        curl_close($ch);
    }

    public function getAllTicketss($page=null,$labels=null,$tab=null,$agent=null,$customer=null,$group=null,$team=null,$priority=null,$type=null,$tag=null,$mailbox=null)
    {
        $access_token = $this->_helperData->getAccessToken();
        $company_domain = $this->_helperData->getCompanyDomainName();
        $str = '';
        if (isset($agent)) {
            $str.='&agent='.$agent;
        }

        if (isset($tab)) {
            $str.='&status='.$tab;
        }

        if (isset($customer)) {
            $str.='&customer='.$customer;
        }

        if (isset($page)) {
            $str.='&page='.$page;
        }

        if (isset($group)) {
            $str.='&group='.$group;
        }

        if (isset($team)) {
            $str.='&team='.$team;
        }

        if (isset($priority)) {
            $str.='&priority='.$priority;
        }

        if (isset($type)) {
            $str.='&type='.$type;
        }

        if (isset($tag)) {
            $str.='&tag='.$tag;
        }

        if (isset($mailbox)) {
            $str.='&mailbox='.$mailbox;
        }

        if (isset($labels)) {
            $str=$labels.$str;
        }
        // Return  tickets 
        $url = 'http://'.$company_domain.'.voipkul.com/en/api/tickets.json?'.$str;
        $ch = curl_init($url);
        $headers = array(
        'Authorization: Bearer '.$access_token,
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($output, 0, $header_size);
        $response = substr($output, $header_size);
        if ($info['http_code'] == 200) {
            return json_decode($response);
        } else {
            return false;
        } 
        curl_close($ch);
    }    

    public function getFilterDataFor($filterType)
    {
        $access_token = $this->_helperData->getAccessToken();
        $company_domain = $this->_helperData->getCompanyDomainName();

        // Return  tickets 
        $url = 'http://'.$company_domain.'.voipkul.com/en/api/filters.json?'.$filterType.'=1';
        $ch = curl_init($url);
        $headers = array(
        'Authorization: Bearer '.$access_token,
        );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($output, 0, $header_size);
        $response = substr($output, $header_size);
        if ($info['http_code'] == 200) {
            return json_decode($response);
        } else {
            return false;
        } 
        curl_close($ch);
    }

    public function createTicket($ticketData) 
    {
        $access_token = $this->_helperData->getAccessToken();
        $company_domain = $this->_helperData->getCompanyDomainName();
        // ticket url 
        $url = 'http://'.$company_domain.'.voipkul.com/en/api/tickets.json';

        $data = json_encode($ticketData);
        $ch = curl_init($url);
        $headers = [
            'Authorization: Bearer '.$access_token,
            'Content-type: application/json'
        ];
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        $info = curl_getinfo($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($server_output, 0, $header_size);
        $response = substr($server_output, $header_size);
        if ($info['http_code'] == 200 || $info['http_code'] == 201) {
            $this->_messageManager->addSuccess(__(' Success ! Ticket has been created successfully.'));
            $customerUvdeskId  = $this->_customerSession->getCustomerUvdeskId();
            if (!isset($customerUvdeskId)) {
                $customerEmail = $this->_customerSession->getCustomer()->getEmail();
                $customerUvDeskData = $this->getCustomerFromEmail($customerEmail);
                if (!empty($customerUvDeskData['customers'])) {
                    $customerUvDeskId = $customerUvDeskData['customers'][0]['id'];
                    $this->_customerSession->setCustomerUvdeskId($customerUvDeskId);
                }
            }   
            return true;
        } elseif ($info['http_code'] == 400) {
            $this->_messageManager->addError(__(' Error, request data not valid. (http-code: 400).'));
        } elseif ($info['http_code'] == 404) {
            $this->_messageManager->addError(__('Error, resource not found (http-code: 404)'));
        } else {
            $this->_messageManager->addError(__('Error, HTTP Status Code :%1', $info['http_code']));
        }
        curl_close($ch);
    }

    public function getCustomerFromEmail($customerEmail = null)
    {
        $access_token = $this->_helperData->getAccessToken();
        $company_domain = $this->_helperData->getCompanyDomainName();
        // ticket url 
        $url = 'http://'.$company_domain.'.voipkul.com/en/api/customers.json?email='.$customerEmail;
        $ch = curl_init($url);
        $headers = [
            'Authorization: Bearer '.$access_token
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        $info = curl_getinfo($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($server_output, 0, $header_size);
        $response = substr($server_output, $header_size);
        if ($info['http_code'] == 200 || $info['http_code'] == 201) {
            return json_decode($response, true);
        } elseif ($info['http_code'] == 400) {
            return "";
        }
        curl_close($ch); 
    }

    public function createCustomer()
    {
        $access_token = $this->_helperData->getAccessToken();
        $company_domain = $this->_helperData->getCompanyDomainName();
        // ticket url 
        $url = 'http://'.$company_domain.'.voipkul.com/en/api/customer.json';

        $data = json_encode($ticketData);
        $ch = curl_init($url);
        $headers = [
            'Authorization: Bearer '.$access_token,
            'Content-type: application/json'
        ];
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        $info = curl_getinfo($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($server_output, 0, $header_size);
        $response = substr($server_output, $header_size);
        if ($info['http_code'] == 200 || $info['http_code'] == 201) {
            $this->_messageManager->addSuccess(__(' Success ! Ticket has been created successfully.'));
            return true;
        } elseif ($info['http_code'] == 400) {
            $this->_messageManager->addError(__(' Error, request data not valid. (http-code: 400).'));
        } elseif ($info['http_code'] == 404) {
            $this->_messageManager->addError(__('Error, resource not found (http-code: 404)'));
        } else {
            $this->_messageManager->addError(__('Error, HTTP Status Code :%1', $info['http_code']));
        }
        curl_close($ch); 
    }

    public function getTicketTypes()
    {
        $access_token = $this->_helperData->getAccessToken();
        $company_domain = $this->_helperData->getCompanyDomainName();
        // ticket url 
        $url = 'http://'.$company_domain.'.voipkul.com/en/api/ticket-types.json?';
        $ch = curl_init($url);
        $headers = [
            'Authorization: Bearer '.$access_token
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        $info = curl_getinfo($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($server_output, 0, $header_size);
        $response = substr($server_output, $header_size);
        if ($info['http_code'] == 200 || $info['http_code'] == 201) {
            return $response;
        } elseif ($info['http_code'] == 400) {
            return "";
        }
        curl_close($ch); 
    }

    public function getTicketThread($ticketId = 0)
    {
        $access_token = $this->_helperData->getAccessToken();
        $company_domain = $this->_helperData->getCompanyDomainName();
        // Return  tickets 
        $url = 'http://'.$company_domain.'.voipkul.com/en/api/ticket/'.$ticketId.'/threads.json';
        $ch = curl_init($url);
        $headers = array(
        'Authorization: Bearer '.$access_token,
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($output, 0, $header_size);
        $response = substr($output, $header_size);
        if ($info['http_code'] == 200) {
            return json_decode($response,true);
        } else {
            return false;
        } 
        curl_close($ch);
    }

    public function getSingleTicketData($ticketIncrementId){
        $access_token = $this->_helperData->getAccessToken();
        $company_domain = $this->_helperData->getCompanyDomainName();
        $str = '';
        // Return  tickets 
        $url = 'http://'.$company_domain.'.voipkul.com/en/api/ticket/'.$ticketIncrementId.'.json';
        $ch = curl_init($url);
        $headers = array(
        'Authorization: Bearer '.$access_token,
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($output, 0, $header_size);
        $response = substr($output, $header_size);
        if ($info['http_code'] == 200) {
            return json_decode($response, true);
        } else {
            return false;
        } 
        curl_close($ch);
    }

    public function addReplyToTicket($ticketId,$ticketIncrementId ,$data){
        // echo $email;die; 

        $access_token = $this->_helperData->getAccessToken();
        $company_domain = $this->_helperData->getCompanyDomainName();
        // ticket url 
        $url = 'http://'.$company_domain.'.voipkul.com/en/api/ticket/'.$ticketId.'/threads.json';
        $data = json_encode($data);
        // echo "<pre>";
        // print_r($data);
        // die;
        $ch = curl_init($url);
        $headers = [
            'Authorization: Bearer '.$access_token,
            'Content-type: application/json'
        ];
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        $info = curl_getinfo($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($server_output, 0, $header_size);
        $response = substr($server_output, $header_size);
        if ($info['http_code'] == 200 || $info['http_code'] == 201) {
            $this->_messageManager->addSuccess(__(' Success ! Ticket has been created successfully.'));
            return true;
        } elseif ($info['http_code'] == 400) {
            $this->_messageManager->addError(__(' Error, request data not valid. (http-code: 400).'));
            return false;
        } elseif ($info['http_code'] == 404) {
            $this->_messageManager->addError(__('Error, resource not found (http-code: 404)'));
            return false;
        } else {
            $this->_messageManager->addError(__('Error, HTTP Status Code :%1', $info['http_code']));
            return false; 
        }
        curl_close($ch); 
    }

    public function downloadAttachment($attachmenId)
    {
        $access_token = $this->_helperData->getAccessToken();
        $company_domain = $this->_helperData->getCompanyDomainName();
        // Return  tickets 
        $url = 'http://'.$company_domain.'.voipkul.com/en/api/ticket/attachment/'.$attachmenId.'.json ';
        $ch = curl_init($url);
        $headers = array(
        'Authorization: Bearer '.$access_token,
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($output, 0, $header_size);
        $response = substr($output, $header_size);
        if ($info['http_code'] == 200) {
            return ['response'=>$response,'info'=>$info]    ;
        } else {
            return false;
        } 
        curl_close($ch);
    }
    
    public function trashTicket()
    {
        $access_token = $this->_helperData->getAccessToken();
        $company_domain = $this->_helperData->getCompanyDomainName();
        // Return  tickets 
        $url = 'http://'.$company_domain.'.voipkul.com/en/ /api/ticket/4802/trash.json';
        $ch = curl_init($url);
        $headers = array(
        'Authorization: Bearer '.$access_token,
        );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($output, 0, $header_size);
        $response = substr($output, $header_size);
        if ($info['http_code'] == 200) {
            return ['response'=>$response,'info'=>$info]    ;
        } else {
            return false;
        } 
        curl_close($ch);
    }      
}