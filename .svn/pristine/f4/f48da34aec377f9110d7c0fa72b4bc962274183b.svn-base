-- Insert SQL here.

ALTER TABLE `dealer` DROP `city` ,
DROP `state` ,
DROP `groupid` ;

ALTER TABLE `vehicle` ADD `submission_date` DATETIME NOT NULL ,
ADD `approval_date` DATETIME NOT NULL ;

ALTER TABLE `maintenance` ADD `submission_date` DATETIME NOT NULL ,
ADD `approval_date` DATETIME NOT NULL ;

ALTER TABLE `maintenance_history` ADD `submission_date` DATETIME NOT NULL ,
ADD `approval_date` DATETIME NOT NULL ;

ALTER TABLE `accident_history` ADD `submission_date` DATETIME NOT NULL ,
ADD `approval_date` DATETIME NOT NULL ;

ALTER TABLE `accident` ADD `submission_date` DATETIME NOT NULL ,
ADD `approval_date` DATETIME NOT NULL ;

ALTER TABLE `maintenance_history` ADD `approval_notes` VARCHAR( 150 ) NOT NULL ;

ALTER TABLE `maintenance` ADD `invoice_file_name` VARCHAR( 50 ) NOT NULL AFTER `file_name` ;

ALTER TABLE `maintenance_history` ADD `invoice_file_name` VARCHAR( 50 ) NOT NULL AFTER `file_name` ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 107, NOW(), 'Sanket Sheth','Dealer Changes');
