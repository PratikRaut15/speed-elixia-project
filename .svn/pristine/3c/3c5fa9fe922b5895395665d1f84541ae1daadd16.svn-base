-- Insert SQL here.

alter table user add column delivery_vehicleid int(11) default null;
ALTER TABLE `user` CHANGE `delivery_vehicleid` `delivery_vehicleid` INT( 11 ) NOT NULL DEFAULT '0';

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
 VALUES ( 217, NOW(), 'Akhil VL','vehicleid in user table');
