INSERT INTO `dbpatches` 
VALUES(5,'2018-09-26 17:44:00','Kartik Joshi','Unit Backup',0);

ALTER TABLE `unitBackup` ADD COLUMN `startTime` DATETIME;
ALTER TABLE `unitBackup` ADD COLUMN `endTime` DATETIME;
ALTER TABLE `unitBackup` ADD COLUMN `timeTaken` DECIMAL(9,6);
ALTER TABLE `unitBackup` ADD COLUMN `errorLog` VARCHAR(100);

UPDATE `dbpatches` SET isapplied = 1 WHERE patchid = 5;
