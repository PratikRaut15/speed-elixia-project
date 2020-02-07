
ALTER TABLE area_master CHANGE `lat` `lat` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE area_master CHANGE `lng` `lng` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

ALTER TABLE area_master add is_approved tinyint(1) NOT NULL AFTER update_date;


-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 21, NOW(), 'Shrikanth Suryawanshi','area_master_changes)');


