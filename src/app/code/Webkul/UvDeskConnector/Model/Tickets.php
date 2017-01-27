<?php
/**
* Webkul Software.
*
* @category Webkul
* @package Webkul_UvDeskConnector
* @author Webkul
* @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
* @license https://store.webkul.com/license.html
*/
namespace Webkul\UvDeskConnector\Model;
 
use Magento\Framework\Model\AbstractModel;
 
class Tickets extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Webkul\UvDeskConnector\Model\ResourceModel\Tickets');
    }
}