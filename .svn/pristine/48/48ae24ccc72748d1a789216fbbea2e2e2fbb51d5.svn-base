-- Insert SQL here.

-- Successful. Add the Patch to the Applied Patches table.
ALTER TABLE `servicecall` ADD `discount_code` VARCHAR( 255 ) NOT NULL AFTER `clientname` ,
ADD `discount_amount` VARCHAR( 255 ) NOT NULL AFTER `discount_code` ,
ADD `expected_endtime` DATETIME NOT NULL AFTER `discount_amount` ;


ALTER TABLE `servicemanage` ADD `iscreatedby` INT NOT NULL AFTER `iseditedby` ;
ALTER TABLE `servicecall` CHANGE `discount_amount` `discount_amount` FLOAT( 0 ) NOT NULL ;

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 2, NOW(), 'Vishu','Additional Fields');