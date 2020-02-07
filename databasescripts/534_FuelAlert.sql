INSERT INTO `speed`.`dbpatches` (`patchid`, `patchdate`, `appliedby`, `patchdesc`, `isapplied`)
VALUES ('534', '2017-11-22 14:34:11', 'Shrikant Suryawanshi', 'Cron Fuel Alert Changes', '0');



ALTER TABLE `fuelcron_alertlog` ADD `deltaSum` DECIMAL(7,2) NOT NULL AFTER `threshold_conflict_status`;


UPDATE speed.dbpatches SET isapplied=1 WHERE patchid = 534;
