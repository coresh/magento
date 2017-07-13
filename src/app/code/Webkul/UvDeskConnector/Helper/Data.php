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
 * UvDeskConnector data helper.
 */
class Data extends AbstractHelper
{
    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    protected $_resourceConfig;

    /**
     * @param \Magento\Framework\App\Helper\Context      $context
     * @param \Magento\Config\Model\ResourceModel\Config $resourceConfig
     * @param \Magento\Customer\Model\Session            $customerSession
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Config\Model\ResourceModel\Config $resourceConfig,
        \Magento\Customer\Model\Session $customerSession
    ) 
    {
        $this->_resourceConfig = $resourceConfig;
        $this->_customerSession = $customerSession;
        parent::__construct($context);
    }

    /**
     * Return the status of module.
     *
     * @return Boolean.
     */
    public function getAvilabilityOfUvdesk()
    {
        $status =  $this->scopeConfig
                                 ->getValue(
                                     'uvdesk_conn/uvdesk_config/uvdesk_status',
                                     \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                                 );
        return $status;
    } 

    /**
     * Return the access token.
     *
     * @return String.
     */
    public function getAccessToken()
    {
        $accessToken =  $this->scopeConfig
                                 ->getValue(
                                     'uvdesk_conn/uvdesk_config/uvdesk_accesstoken',
                                     \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                                 );
        return $accessToken;
    }  

    /**
     * Return the company domain name.
     *
     * @return String.
     */
    public function getCompanyDomainName()
    {
        $companyDomainName =  $this->scopeConfig
                                 ->getValue(
                                     'uvdesk_conn/uvdesk_config/uvdesk_companydomain',
                                     \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                                 );
        return $companyDomainName;
    }

    /**
     * Return the status of customer log in.
     *
     * @return Boolean.
     */
    public function isLoggedIn() {
        return $this->_customerSession->isLoggedIn();
    }

    /**
     * Return the secret key for encoding of customer data for SSO.
     *
     * @return String.
     */
    public function getSecretket() 
    {
        $secretkey =  $this->scopeConfig
                                 ->getValue(
                                     'uvdesk_conn/uvdesk_config_sso/uvdesk_sso_secret_key',
                                     \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                                 );
        return $secretkey;
    }

    /**
     * Return the redirecting url for SSO.
     *
     * @return String.
     */
    public function getRedirectUrl() 
    {
        $url =  $this->scopeConfig
                                 ->getValue(
                                     'uvdesk_conn/uvdesk_config_sso/uvdesk_sso_redirect_url',
                                     \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                                 );
        return $url;
    }
}