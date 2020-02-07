INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'416', NOW(), 'Shrikant Suryawanshi', 'CheckPoint Exception', '0'
);


Create table checkPointException(
chkExpId int NOT NULL PRIMARY KEY AUTO_INCREMENT,
exceptionId int NOT NULL,
checkPointId int NOT NULL,
vehicleId int NOT NULL,
startTime varchar(10) NOT NULL,
endTime varchar(10) NOT NULL,
exceptionType tinyint NOT NULL COMMENT '1-CheckPoint In, 2-Checkpoint Out',
userId int NOT NULL,
isSend tinyint DEFAULT 0,
customerno int NOT NULL,
created_by int NOT NULL,
created_on datetime,
updated_by int NOT NULL,
updated_on datetime,
isdeleted tinyint DEFAULT 0
);



DELIMITER $$
DROP PROCEDURE IF EXISTS `insertCheckpointException`$$
CREATE PROCEDURE `insertCheckpointException`(
    In exceptionParam int
	, IN exceptionNameParam varchar(100)
	, IN vehicleParam varchar(100)
	, IN checkpointParam varchar(100)
	, IN startTimeParam varchar(10)
	, IN endTimeParam varchar(10)
	, In custno int
	, IN useridParam int
	, IN todaydate DATETIME
)
BEGIN
	DECLARE exceptionIdParam int;
	DECLARE noOfVehicles INT;
	DECLARE noOfCheckpoints INT;
    DECLARE tempCount INT;
	DECLARE tempCountCheckpoint INT;

	SET  @noOfCommas = 0;
	SET  @noOfCommasCheckpoint = 0;
	SET @vehicleIdParam = 0;
	SET @checkpointIdParam = 0;

    IF(custno = 0) THEN
        SET custno = NULL;
    END IF;

    SELECT max(exceptionId) + 1 into
	exceptionIdParam
	From checkPointException;

	IF(exceptionIdParam IS NULL) THEN
		SET exceptionIdParam = 1;
	END IF;

	IF (exceptionIdParam IS NOT NULL) THEN
		BEGIN
			SELECT LENGTH(vehicleParam) - LENGTH(REPLACE(vehicleParam, ',', '')) INTO @noOfCommas;
            SET noOfVehicles = @noOfCommas + 1;
            SET tempCount = 1;
			WHILE (tempCount  <=  noOfVehicles) DO
				SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(vehicleParam, ',', tempCount), ',', -1 ) INTO @vehicleIdParam;

					SELECT LENGTH(checkpointParam) - LENGTH(REPLACE(checkpointParam, ',', '')) INTO @noOfCommasCheckpoint;
					SET noOfCheckpoints = @noOfCommasCheckpoint + 1;
					SET tempCountCheckpoint = 1;
					WHILE (tempCountCheckpoint  <=  noOfCheckpoints) DO
						SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(checkpointParam, ',', tempCountCheckpoint), ',', -1 ) INTO @checkpointIdParam;
						INSERT INTO checkPointException
						(exceptionId
						, exceptionName
						, checkPointId
						, vehicleId
						, startTime
						, endTime
						, exceptionType
						, customerno
						, created_by
						, created_on
						) VALUES (
						exceptionIdParam
						, exceptionNameParam
						, @checkpointIdParam
						, @vehicleIdParam
						, startTimeParam
						, endTimeParam
						, exceptionParam
						, custno
						, useridParam
						, todaydate
						);
						SET  tempCountCheckpoint = tempCountCheckpoint + 1;
					END WHILE;

			    SET  tempCount = tempCount + 1;
			END WHILE;
		END;
	END IF;

END$$
DELIMITER ;



INSERT INTO `modules` (`moduleid`, `modulename`, `created_by`, `updated_by`, `created_on`, `updatd_on`, `isdeleted`) VALUES ('5', 'Checkpoint Exception', '491', '491', '2016-09-29 02:00:00', '2016-09-29 02:00:00', '0');

/* Modifications */

ALTER TABLE `checkPointException` ADD `checkpointName` VARCHAR(100) NOT NULL AFTER `exceptionId`;
ALTER TABLE `checkPointException` CHANGE `checkpointName` `exceptionName` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

Create Table chkptExUserMapping(
chkExUserMappingId INT NOT  NULL PRIMARY KEY AUTO_INCREMENT,
chkExId INT NOT NULL ,
userId INT NULL,
customerno int NOT NULL,
created_by int NOT NULL,
created_on datetime,
updated_by int NOT NULL,
updated_on datetime,
isdeleted tinyint DEFAULT 0
);

Create Table chkptExOccuredLog(
chkptExOccLogId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
exceptionId int NOT NULL,
chkptId int NOT NULL,
vehicleId int NOT NULL,
message varchar(160) NOT NULL,
customerno int NOT NULL,
created_by int NOT NULL,
created_on datetime,
updated_by int NOT NULL,
updated_on datetime,
isdeleted tinyint DEFAULT 0
);

Create Table chkptExAlertLog(
chkptExAlertId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
chkptExOccLogId int NOT NULL,
chkExUserMappingId int NOT NULL,
isSMS tinyint(1) NOT NULL,
isEmail tinyint(1) NOT NULL,
isTelephone tinyint(1) NOT NULL,
isGcm tinyint(1) NOT NULL,
customerno int NOT NULL,
created_by int NOT NULL,
created_on datetime,
updated_by int NOT NULL,
updated_on datetime,
isdeleted tinyint(1) DEFAULT 0
);

Create Table chkptExErrorLog(
chkptExErrLogId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
chkptExAlertId INT NOT NULL,
errorNo int NOT NULL,
customerno int NOT NULL,
created_by int NOT NULL,
created_on datetime,
updated_by int NOT NULL,
updated_on datetime,
isdeleted tinyint(1) DEFAULT 0
);


Create Table ErrorMsgMaster(
errorNo int NOT NULL PRIMARY KEY AUTO_INCREMENT,
errorName varchar(150) NOT NULL,
errorMsg varchar(255) NOT NULL,
customerno int NOT NULL,
created_by int NOT NULL,
created_on datetime,
updated_by int NOT NULL,
updated_on datetime,
isdeleted tinyint(1) DEFAULT 0
);


Create Table alertTypeMaster(
alertTypeId int not null primary key auto_increment,
alertType varchar(15),
customerno int NOT NULL,
created_by int NOT NULL,
created_on datetime,
updated_by int NOT NULL,
updated_on datetime,
isdeleted tinyint(1) DEFAULT 0
);

Create Table alertMaster(
alertId int not null primary key auto_increment,
alert varchar(50),
customerno int NOT NULL,
created_by int NOT NULL,
created_on datetime,
updated_by int NOT NULL,
updated_on datetime,
isdeleted tinyint(1) DEFAULT 0
);

Create Table userAlertMapping(
alertMappingId int not null primary key auto_increment,
userId int not null,
alertId int not null,
alertTypeId int not null,
isActive tinyint(1) not null,
customerno int NOT NULL,
created_by int NOT NULL,
created_on datetime,
updated_by int NOT NULL,
updated_on datetime,
isdeleted tinyint(1) DEFAULT 0
);


INSERT INTO `alertTypeMaster`(`alertType`) VALUES
('SMS'),('Email'),('Telephonic'),('FCM');


INSERT INTO `alertMaster`(`alert`) VALUES
('Checkpoint Exception');



ALTER TABLE `checkPointException` CHANGE `startTime` `startTime` TIME NOT NULL, CHANGE `endTime` `endTime` TIME NOT NULL;



Create Table emailLog(
emailLogId int NOT NULL PRIMARY KEY AUTO_INCREMENT,
emailid varchar(100) NOT NULL,
emailSubject varchar(250) NOT NULL,
emailMessage text NOT NULL,
vehicleid int not null,
userid int not null,
moduleid int not null,
typeid int not null,
customerno int not null,
isMailSent tinyint(1),
created_on datetime,
isdeleted tinyint(1) default 0
);

ALTER TABLE `checkpoint` ADD `isSMS` TINYINT(1) NOT NULL AFTER `eta_starttime`, ADD `isEmail` TINYINT(1) NOT NULL AFTER `isSms`;
ALTER TABLE `checkpoint` CHANGE `isSMS` `isSms` TINYINT( 1 ) NOT NULL

update checkpoint SET isSms = 1, isEmail = 1 WHERE customerno = 328;

ALTER TABLE `emailLog` CHANGE `typeid` `type` INT( 11 ) NOT NULL

INSERT INTO `ErrorMsgMaster`(`errorName`,`errorMsg`) VALUES
('Mobile Not Available','Mobile / Phone No not available to send sms.'),
('Insufficient SMS Balance','Your sms balance is insufficient to send sms.'),
('Email Not Available','Email Id not available.'),
('Gcm Id Not AVailable','Gcm Id not available.'),
('SMS API Issue','Sms not sent due to SMS API Issue.'),


UPDATE 	dbpatches
SET 	patchdate = NOW()
	, isapplied =1
WHERE 	patchid = 416;
