INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('523', '2017-07-15 12:30:00','Arvind Thakur','Team Customer Lock', '0');


CREATE TABLE IF NOT EXISTS `customer_lock_log` (
    `id` INT(11) PRIMARY KEY auto_increment,
    `customerno` INT(11),
    `teamid` INT(11),
    `locked_on` DATETIME,
    `unlocked_on` DATETIME,
    `isdeleted` TINYINT(1) DEFAULT 0
);


UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 523;