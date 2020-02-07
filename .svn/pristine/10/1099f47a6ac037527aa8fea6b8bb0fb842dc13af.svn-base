-- Insert SQL here.

alter table zone_master add column groupid int(11) default 0 after zonename;

update zone_master set groupid=1485 where zone_master_id in(1,2,3,4,5,6,7,8,9,10);
update zone_master set groupid=1459 where zone_master_id in(11,12,13,14,15,16);

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 16, NOW(), 'Akhil VL','Add group column in zone_master');
