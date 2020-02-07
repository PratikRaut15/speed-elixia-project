-- Insert SQL here.

ALTER TABLE `eventalerts` ADD `door` TINYINT( 1 ) NOT NULL ;
ALTER TABLE `eventalerts` ADD `door_time` DATETIME NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 179, NOW(), 'Sanket Sheth','Door Check');
