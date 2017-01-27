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
     * @param \Magento\Framework\App\Helper\Context      $context]
     * @param \Magento\Config\Model\ResourceModel\Config $resourceConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Config\Model\ResourceModel\Config $resourceConfig
    ) 
    {
        $this->_resourceConfig = $resourceConfig;
        parent::__construct($context);
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


    // public function getAccessToken() 
    // {
    //     return 'A289AA1A50DA12175D033D194DE5F5E813781A289AA1A50DA12175D033D194DE5F5E8';
    // }

    // public function getCompanyDomainName()
    // {
    //     return 'testingnew';
    // }
}