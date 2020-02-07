-- Insert SQL here.
alter table vehicle add column max_voltage int(8) default null;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 243, NOW(), 'Akhil','max_voltage in vehicles');
