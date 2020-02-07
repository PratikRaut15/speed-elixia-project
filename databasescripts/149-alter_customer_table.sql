-- Insert SQL here.

alter table customer add column use_advanced_alert tinyint(1) default 0;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 149, NOW(), 'Akhil VL','alter customer table');
