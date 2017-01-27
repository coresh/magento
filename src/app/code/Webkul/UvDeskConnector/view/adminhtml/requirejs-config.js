/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_UvDeskConnector
 * @author    Webkul
 * @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */ 
var config = {
    map :{
    '*': {
        dataTablesjs: "Webkul_UvDeskConnector/js/dataTablesjs",
        bootstrapjs: "Webkul_UvDeskConnector/js/bootstrapjs"
    },
    shim: {
        dataTablesjs: {
            'deps': ['jquery']
        },
        bootstrapjs: {
            'deps': ['jquery']
        }
    }
}
};