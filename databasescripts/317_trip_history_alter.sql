-- Insert SQL here.
ALTER TABLE `tripdetail_history` ADD `consignorid` INT(11) NOT NULL AFTER `consignee`, ADD `consigneeid` INT(11) NOT NULL AFTER `consignorid`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 317, NOW(), 'ganesh','alter tripdetail_history');
