-- Insert SQL here.

ALTER TABLE `stoppage_alerts` ADD INDEX ( `vehicleid` ) ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 273, NOW(), 'Sanket Sheth','Vehicle ID Indexed');
