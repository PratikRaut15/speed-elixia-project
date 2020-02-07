INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('512', '2017-06-16 16:46:00','Arvind Thakur','speed support task', '0');

ALTER TABLE `sp_note`
ADD COLUMN `is_customer` TINYINT(2) DEFAULT 0 AFTER `create_by`;

ALTER TABLE `sp_ticket`
ADD COLUMN `create_platform` TINYINT(2) DEFAULT 1 COMMENT '1-web,2-android,3-iOS';


UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 512;