-- Insert SQL here.

ALTER TABLE `team` CHANGE `distributor_id` `distributor_id` INT(11) NOT NULL DEFAULT '0', CHANGE `address` `address` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 207, NOW(), 'Ganesh','alter team distributor_id and address');
