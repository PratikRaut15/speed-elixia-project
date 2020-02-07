-- Insert SQL here.

ALTER TABLE `customer` ADD `use_cheque` BOOL NOT NULL ,
ADD `use_partialpayment` BOOL NOT NULL ;

ALTER TABLE `payment` ADD `bank` VARCHAR( 50 ) NOT NULL AFTER `accountno` ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 33, NOW(), 'Sanket Sheth','Use Fields for Customer');