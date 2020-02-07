INSERT INTO `dbpatches` 
VALUES(4,'2018-09-25 18:53:00','Kartik Joshi','Unit Backup',0);

DROP TABLE IF EXISTS `unitBackup`;
CREATE TABLE `elixiatech`.`unitBackup` (
	`ubId` INT(11) AUTO_INCREMENT PRIMARY KEY,
	`unitNo` VARCHAR(16) NOT NULL,
    `customerNo` INT(11) NOT NULL,
    `isProcessed` TINYINT(2) DEFAULT 0,
    `timestamp` DATETIME COMMENT 'last run'
);

UPDATE `dbpatches` SET isapplied = 1 WHERE patchid = 4;
