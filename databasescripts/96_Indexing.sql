-- Insert SQL here.

ALTER TABLE `ignitionalert` ADD INDEX ( `vehicleid` ) ;
ALTER TABLE `devices` ADD INDEX ( `uid` ) ;
-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 96, NOW(), 'Sanket Sheth','Indexing');
