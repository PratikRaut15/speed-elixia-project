-- Insert SQL here.

ALTER TABLE `dailyreport` ADD INDEX ( `vehicleid` ) ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 215, NOW(), 'Sanket Sheth','Vehicle ID Indexed');
