-- Insert SQL here.

alter table customer add column use_fuel_sensor tinyint(1) default 0 after use_genset_sensor;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 192, NOW(), 'Akhil VL','added fuel sensor in customer');
