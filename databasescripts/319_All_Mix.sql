-- Insert SQL here.

RENAME TABLE invoice_customer TO accounts_details;
RENAME TABLE accounts_details TO cust_account_details;
ALTER TABLE `cust_account_details` CHANGE `invoicename` `name` VARCHAR( 21 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;
ALTER TABLE `cust_account_details` ADD `email` VARCHAR( 40 ) NOT NULL ,
ADD `phone` INT( 20 ) NOT NULL ,
ADD `panno` VARCHAR( 30 ) NOT NULL ,
ADD `tinno` VARCHAR( 30 ) NOT NULL ;

UPDATE `customtype` SET `name` = 'Analog 1' WHERE `customtype`.`id` =20;
UPDATE `customtype` SET `name` = 'Analog 2' WHERE `customtype`.`id` =21;
UPDATE `customtype` SET `name` = 'Analog 3' WHERE `customtype`.`id` =22;
UPDATE `customtype` SET `name` = 'Analog 4' WHERE `customtype`.`id` =23;

RENAME TABLE commercial_details TO customer_notes;
ALTER TABLE `customer_notes` CHANGE `cdid` `cnid` INT( 11 ) NOT NULL AUTO_INCREMENT ;
ALTER TABLE `customer_notes` CHANGE `comdetails` `details` VARCHAR( 500 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;

ALTER TABLE `customer` ADD `commercial_details` VARCHAR( 500 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 319, NOW(), 'Sanket Sheth','All_Mix');
