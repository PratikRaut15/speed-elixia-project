-- Insert SQL here.

alter table user add column delivery_vehicleid int(11) default null;


-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
 VALUES ( 216, NOW(), 'Akhil VL','vehicleid in user table');
