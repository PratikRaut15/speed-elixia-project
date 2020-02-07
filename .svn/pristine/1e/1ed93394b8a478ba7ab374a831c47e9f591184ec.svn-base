INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('500', '2017-05-03 02:19:00', 'Ganesh Papde', 'Add record', '0');


INSERT INTO `speed`.`reportMaster` (`reportId`, `reportName`, `is_warehouse`, `customerno`, `isdeleted`) VALUES ('15', 'Summary Report ', '0', '170', '0');	

INSERT INTO `speed`.`userReportMapping` (`userReportId`, `reportId`, `reportTime`, `isActivated`, `userid`, `customerno`, `created_by`, `created_on`, `updated_by`, `updated_on`, `isdeleted`) VALUES (NULL, '15', '9', '1', '838', '170', '838', '2017-05-02 00:00:00', '', NULL, '0');
INSERT INTO `speed`.`userReportMapping` (`userReportId`, `reportId`, `reportTime`, `isActivated`, `userid`, `customerno`, `created_by`, `created_on`, `updated_by`, `updated_on`, `isdeleted`) VALUES (NULL, '15', '9', '1', '1274', '170', '1274', '2017-05-02 00:00:00', '', NULL, '0');




UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 500;


