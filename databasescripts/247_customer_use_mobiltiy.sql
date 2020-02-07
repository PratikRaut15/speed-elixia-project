-- Insert SQL here.

alter table customer add column use_mobility tinyint(1) default 0 after use_routing;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 247, NOW(), 'Akhil VL','added use_mobilty in customer');
