INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('522', '2017-07-13 12:30:00','Ganesh Papde','alter user table for docs', '0');

ALTER TABLE `user` ADD `smsalert_status` TINYINT(2) NOT NULL DEFAULT '0' , ADD `emailalert_status` TINYINT(2) NOT NULL DEFAULT '0' ;


UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 522;
