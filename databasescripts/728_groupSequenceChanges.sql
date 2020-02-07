SET @patchId = 728;
SET @patchDate = '2019-10-11 04:15:00';
SET @patchOwner = 'Pratik Raut';
SET @patchDescription = 'Group Sequence Changes';


INSERT INTO dbpatches(patchid, patchdate, appliedby, patchdesc, isapplied)
VALUES (@patchId, @patchDate, @patchOwner, @patchDescription, '0');

ALTER TABLE `group` CHANGE `sequence1` `sequence` TINYINT(4) NOT NULL DEFAULT '0';


UPDATE dbpatches SET isapplied = 1, updatedOn =  DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE) WHERE patchid = @patchId;
