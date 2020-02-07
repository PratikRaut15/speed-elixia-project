ALTER TABLE `discount` ADD `isupgrade` INT NOT NULL AFTER `clientid` ;



 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 16, NOW(), 'vishwanath','discount isupgrade');