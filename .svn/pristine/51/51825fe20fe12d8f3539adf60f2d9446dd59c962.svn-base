-- Insert SQL here.

alter table customer add column use_ac_sensor tinyint(1) default 0 after use_door_sensor;
alter table customer add column use_genset_sensor tinyint(1) default 0 after use_ac_sensor;


update customer set use_ac_sensor=1 where customerno in (3,43,47,64,78,83,93,98,104,106,107,110);
update customer set use_genset_sensor=1 where customerno in (9,35,39,59,65,76,91,108,116,121,22,123,138);


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
 VALUES ( 190, NOW(), 'Akhil VL','ac/genset columns in customer table');
