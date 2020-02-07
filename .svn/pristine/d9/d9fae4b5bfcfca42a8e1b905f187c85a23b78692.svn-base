-- Insert SQL here.

ALTER TABLE `sp_ticket_details` ADD `created_type` TINYINT(1) NOT NULL DEFAULT '0' ;  
ALTER TABLE `sp_ticket_details` ADD `userid` INT(11) NOT NULL ;
ALTER TABLE `sp_ticket_details` ADD `is_custupdated` TINYINT(1) NOT NULL DEFAULT '0' ;
ALTER TABLE `sp_ticket` ADD `uid` INT(11) NOT NULL;
ALTER TABLE `sp_ticket` ADD `created_type` TINYINT(1) NOT NULL DEFAULT '0' ;  
ALTER TABLE `sp_ticket` ADD `crmid` INT(11) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 250, NOW(), 'Ganesh','Customer can genrate tickets for crm');

