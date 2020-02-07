-- Insert SQL here.
ALTER TABLE `communicationqueue` ADD `is_notif` BOOL NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 22, NOW(), 'Sanket Sheth','Notification');
