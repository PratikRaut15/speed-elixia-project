-- Insert SQL here.

ALTER TABLE `unit` ADD INDEX ( `vehicleid` ) ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 194, NOW(), 'Akhil','Index Unit Table');
