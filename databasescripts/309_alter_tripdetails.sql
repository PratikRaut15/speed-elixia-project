ALTER TABLE `tripdetails` ADD `remark` VARCHAR(200) NOT NULL AFTER `drivermobile2`, ADD `perdaykm` INT(11) NOT NULL AFTER `remark`;
-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (309, NOW(), 'ganesh','alter tripdetails');

