-- Insert SQL here.

truncate routing_map;
alter table routing_map add column isdeleted tinyint(1) default 0;

-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 226, NOW(), 'Akhil VL','routing table changes');
