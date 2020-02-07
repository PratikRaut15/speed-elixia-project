-- Insert SQL here.
ALTER TABLE `ignitionalert` ADD INDEX ( `vehicleid` );

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 37, NOW(), 'Ajay Tripathi','Index in ignitionalert table');
