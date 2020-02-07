-- Insert SQL here.

ALTER TABLE `unit` ADD `comments` VARCHAR( 50 ) NOT NULL ;
ALTER TABLE `trans_history` ADD `comments` VARCHAR( 50 ) NOT NULL ;
ALTER TABLE `simcard` ADD `comments` VARCHAR( 50 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 138, NOW(), 'Sanket Sheth','Add Comments');
