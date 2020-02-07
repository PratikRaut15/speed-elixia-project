SET @patchId = 748;
SET @patchDate = '2020-01-16 11:34:00';
SET @patchOwner = 'Arvind Thakur';
SET @patchDescription = 'groupwise temperature report';

INSERT INTO dbpatches(patchid, patchdate, appliedby, patchdesc, isapplied)
VALUES (@patchId, @patchDate, @patchOwner, @patchDescription, '0');

INSERT INTO `reportMaster` (`reportId`, `reportName`, `is_warehouse`, `customerno`, `isdeleted`) 
VALUES (22, 'Group Wise Temperature Report', '0', '991', '0');

UPDATE dbpatches SET isapplied = 1, updatedOn =  DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE) WHERE patchid = @patchId;
