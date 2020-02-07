-- Insert SQL here.

alter table slot_master add column isdeleted tinyint(1) default 0;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 14, NOW(), 'Akhil VL','added isdeleted in slot_master');
