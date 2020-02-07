
ALTER TABLE `tripdetails` ADD `vehicleid` INT(11) NOT NULL AFTER `vehicleno`;
ALTER TABLE `tripdetail_history` ADD `vehicleid` INT(11) NOT NULL AFTER `vehicleno`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (304, NOW(), 'Ganesh','trip alter tables');
