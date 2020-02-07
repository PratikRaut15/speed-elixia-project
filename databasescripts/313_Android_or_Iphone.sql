-- Insert SQL here.

ALTER TABLE `login_history` ADD `phonetype` BOOLEAN NOT NULL DEFAULT '0';
ALTER TABLE `login_history` ADD `version` VARCHAR( 10 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 313, NOW(), 'Sanket Sheth','Android or IPhone');
