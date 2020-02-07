INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('518', '2017-06-30 16:04:00','Arvind Thakur','Automated Invoice Generate', '0');


ALTER TABLE `invoice`
ADD COLUMN  `timestamp` DATETIME,
ADD COLUMN  `is_mail_sent` TINYINT(1) DEFAULT 1,
ADD COLUMN  `quantity` INT(11);

UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 518;