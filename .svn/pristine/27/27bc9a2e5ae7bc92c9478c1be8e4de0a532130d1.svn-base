ALTER TABLE `client` ADD `expiry_date` DATETIME NOT NULL AFTER `userid` ,
ADD `type_id` INT NOT NULL AFTER `expiry_date` ,
ADD `status` INT NOT NULL AFTER `type_id` ;




INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 23, NOW(), 'vishu','client alteration type');