-- Insert SQL here.

alter table customer add column use_door_sensor tinyint(1) default 0 after use_advanced_alert;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 180, NOW(), 'Akhil VL','added Door in customer');
