INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('501', '2017-05-05 13:00:00', 'Arvind Thakur', 'add team as new module', '0');

INSERT INTO modules(`moduleid`,`modulename`,`created_by`,`created_on`) values(9,'Team','4','2017-05-05 13:00:00');

UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 501;