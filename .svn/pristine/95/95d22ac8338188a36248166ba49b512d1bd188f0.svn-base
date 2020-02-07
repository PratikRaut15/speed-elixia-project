-- Insert SQL here.

ALTER TABLE `tripdetails` ADD `odometer` VARCHAR(15) NOT NULL AFTER `tripstatusid`;
ALTER TABLE `tripdetail_history` ADD `odometer` VARCHAR(15) NOT NULL AFTER `tripstatusid`;
ALTER TABLE `tripdetails` ADD `actualhrs` INT(11) NOT NULL AFTER `budgetedhrs`, ADD `actualkms` INT(11) NOT NULL AFTER `actualhrs`;
ALTER TABLE `tripdetail_history` ADD `actualhrs` INT(11) NOT NULL AFTER `budgetedhrs`, ADD `actualkms` INT(11) NOT NULL AFTER `actualhrs`;
ALTER TABLE `tripdetails` ADD `is_tripend` TINYINT(1) NOT NULL DEFAULT '0' AFTER `isdeleted`;
ALTER TABLE `tripdetail_history` ADD `is_tripend` TINYINT(1) NOT NULL DEFAULT '0' AFTER `isdeleted`;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 316, NOW(), 'ganesh','add fields for actualkms/actualhrs/istripend');



