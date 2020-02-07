INSERT INTO `speed`.`dbpatches` (`patchid`, `patchdate`, `appliedby`, `patchdesc`, `isapplied`)
VALUES ('533', '2017-10-12 14:34:11', 'Shrikant Suryawanshi', 'Cron Mail isQueued Changes', '0');



ALTER TABLE `comqueue` ADD `isQueued` TINYINT(1) NOT NULL DEFAULT '0' AFTER `tempsensor`;


UPDATE speed.dbpatches SET isapplied=1 WHERE patchid = 533;
