-- Insert SQL here.


alter table vehicle add column fuel_min_volt float(10,2) default 0 after max_voltage;
alter table vehicle add column fuel_max_volt float(10,2) default 0 after fuel_min_volt;



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (272, NOW(), 'Akhil','Alter vehicle Table');
