SET @patchId = 727;
SET @patchDate = '2019-10-01 04:05:00';
SET @patchOwner = 'Pratik Raut';
SET @patchDescription = 'Stored procedure written for Inserting checkpoint';


INSERT INTO dbpatches(patchid, patchdate, appliedby, patchdesc, isapplied)
VALUES (@patchId, @patchDate, @patchOwner, @patchDescription, '0');

ALTER TABLE `checkpointmanage` ADD `isErp` ENUM('0','1') NOT NULL DEFAULT '0' AFTER `customerno`;

DROP procedure IF EXISTS `insertCheckPoint`;

DELIMITER $$
CREATE  PROCEDURE `insertCheckPoint`(IN `latitude` VARCHAR(255), IN `longitude` VARCHAR(255), IN `caddress` VARCHAR(255), IN `customerno` VARCHAR(255), IN `userid` VARCHAR(255), IN `checkpointname` VARCHAR(255), IN `radius` VARCHAR(100), IN `todaysdateParam` DATETIME, IN `query` VARCHAR(30), OUT `checkpointIdparam` INT(11))
BEGIN
	IF query <> '' AND query = 'i' THEN
		START TRANSACTION;
			BEGIN
				INSERT INTO `checkpoint`(`checkpointid`, `customerno`, `cname`, `chktype`, `cadd`, `cgeolat`, `cgeolong`, `crad`, `userid`, `phoneno`, `email`, `eta`, `eta_starttime`, `isSms`, `isEmail`, `checkPointCategory`, `polygonLatLongJson`, `isdeleted`) 
				VALUES (NULL, `customerno`, `cname`, 0, `caddress`, `latitude`, `longitude`, `radius`, userid, '', '', '00:00:00', `todaysdateParam`, '', '', '1', '', '0');	
			
			END;
		COMMIT;
	END IF;
	
SET checkpointIdparam = LAST_INSERT_ID();

END$$
DELIMITER ;


UPDATE dbpatches SET isapplied = 1, updatedOn =  DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE) WHERE patchid = @patchId;
