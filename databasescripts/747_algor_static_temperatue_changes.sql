SET @patchId = 747;
SET @patchDate = '2020-01-13 14:01:00';
SET @patchOwner = 'Shrikant Suryawanshi';
SET @patchDescription = 'Algor Static Temperature Changes';

INSERT INTO dbpatches(patchid, patchdate, appliedby, patchdesc, isapplied)
VALUES (@patchId, @patchDate, @patchOwner, @patchDescription, '0');

ALTER TABLE `vehicle` ADD `staticTemp1` TINYINT NOT NULL DEFAULT '0' AFTER `vehicle_status`, 
ADD `staticTemp2` TINYINT NOT NULL DEFAULT '0' AFTER `staticTemp1`, 
ADD `staticTemp3` TINYINT NOT NULL DEFAULT '0' AFTER `staticTemp2`, 
ADD `staticTemp4` TINYINT NOT NULL DEFAULT '0' AFTER `staticTemp3`;


UPDATE dbpatches SET isapplied = 1, updatedOn =  DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE) WHERE patchid = @patchId;
