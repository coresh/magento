# UVdesk
Uvdesk Is an online SAAS based customer support help-desk software which allows customers to communicate over email , Social media with task management.

### UVdesk module for Magento2

Using this module, you can integrate UVdesk with your Magento2 site. From where 
- You will be able to view all tickets.
- Reply tickets from your Magento2 admin panel with other options like *Assignment*, *Update* and many more.
- Magento2 customers will be able to see all tickets in there dashboard. Customers can add more ticket, can communicate.

### Documentation 
If you are having trouble understanding workflow of this module then this is for you :)
[How to use.
](http://webkul.com/blog/uvdesk-magento2-free-helpdesk-ticket-system/)
 
### Installation

Install through composer:
- Run Following Command via terminal  
	``` composer config repositories.magento2_uv_desk_connector vcs https://github.com/uvdesk/magento.git ```  
	``` composer require webkul/uvdeskconnector:dev-master ```  
	``` php bin/magento setup:upgrade ```  
	``` php bin/magento setup:di:compile ```  
	``` php bin/magento setup:static-content:deploy ```
-  Flush the cache and reindex all.  
  

Manual Installation:
-  Unzip the respective extension zip and then move "app" folder (inside "src" folder) into magento root directory.
-  Run Following Command via terminal  
	``` php bin/magento setup:upgrade ```  
	``` php bin/magento setup:di:compile ```  
	``` php bin/magento setup:static-content:deploy ```  
-  Flush the cache and reindex all.

### Contributing
This is an open source project. If you'd like to contribute
 - Fork it.
 - Add your branch using 
    ``` git checkout -b your-branch ```
 - Add your changes and commit
    ``` git commit -m "your changes ```
 - Push to your branch
    ``` git push -u origin your-branch ```
 - Create new pull request.