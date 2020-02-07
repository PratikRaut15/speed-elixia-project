-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'402', '2016-08-20 18:11:01', 'Arvind Thakur', 'create page_master table and login_history_details', '0'
);


-- Insert SQL here.

CREATE TABLE page_master(
    page_master_id int(11) PRIMARY KEY AUTO_INCREMENT,
    page_master_name varchar(50),
    is_web tinyint(1));

CREATE TABLE login_history_details(
	logHistoryId int AUTO_INCREMENT,
	page_master_id int NOT Null,
	customerno int,	
	created_on datetime,
	created_by int,
	PRIMARY KEY(logHistoryId),
	FOREIGN KEY(page_master_id) REFERENCES page_master(page_master_id)
);

CREATE TABLE loginTypeMaster(
id int PRIMARY KEY AUTO_INCREMENT,
loginKey int ,
loginType varchar(25)
);

INSERT INTO `loginTypeMaster` (`id`, `loginKey`, `loginType`) VALUES
(1, 0, 'Web'),
(2, 1, 'Android'),
(3, 2, 'IPhone');

INSERT INTO `page_master` (`page_master_id`, `page_master_name`, `is_web`) VALUES
(1, 'Real Time - Data', 0),
(2, 'Real Time - Map', 0),
(3, 'Reports - Route History', 0),
(4, 'Reports - Travel History', 0),
(5, 'Reports - Alert History', 0),
(6, 'Reports - Location History', 0),
(7, 'Reports - Stoppage History', 0),
(8, 'Reports - Speed History', 0),
(9, 'Reports - Trip Report', 0),
(10, 'Reports - Checkpoint Report', 0),
(11, 'Reports - Distance Report', 0),
(12, 'Reports - Idle Time Report', 0),
(13, 'Reports - Overspeed Report', 0),
(14, 'Reports - Fence Conflict Report', 0),
(15, 'Reports - Location Report', 0),
(16, 'Reports - Checkpoint Report', 0),
(17, 'Reports - Enhanced Route Report', 0),
(18, 'Reports - Stoppage Analysis', 0),
(19, 'Reports - Summary', 0),
(20, 'Reports - Informatics', 0),
(21, 'Reports - Route Summary', 0),
(22, 'Reports - Exception', 0),
(23, 'Master - Vehicles', 0),
(24, 'Master - Drivers', 0),
(25, 'Master - Users', 0),
(26, 'Master - Simple Checkpoint', 0),
(27, 'Master- Enhanced Checkpoint', 0),
(28, 'Master - Fences', 0),
(29, 'Master - Client Code', 0),
(30, 'Master - Route', 0),
(31, 'Master - Group', 0),
(32, 'Admin - My Account', 0),
(33, 'Admin - Alerts', 0),
(34, 'Admin - Support', 0),
(35, 'Admin - Login History', 0),
(36, 'Admin - Log Out', 0),
(37, 'Android - Map', 1),
(38, 'Android - Real Time Data', 1),
(39, 'Android - Vehicle Details', 1),
(40, 'Android - Route History', 1),
(41, 'Android - Alert History', 1),
(42, 'Android - Summary', 1),
(43, 'Android - Contract', 1),
(44, 'Android - Support', 1),
(45, 'Android - Logout', 1),
(46, 'Android - Dashboard', 1),
(47, 'IPhone - Map', 2),
(48, 'IPhone - Real Time Data', 2),
(49, 'IPhone - Vehicle Details', 2),
(50, 'IPhone - Route History', 2),
(51, 'IPhone - Alert History', 2),
(52, 'IPhone - Summary', 2),
(53, 'IPhone - Contract', 2),
(54, 'IPhone - Support', 2),
(55, 'IPhone - Logout', 2),
(56, 'IPhone - Dashboard', 2);

ALTER TABLE page_master
ADD COLUMN page_url varchar(100) AFTER page_master_name;

UPDATE `page_master` SET `page_url`='realtimedata.php' WHERE page_master_id=1;
UPDATE `page_master` SET `page_url`='map.php' WHERE page_master_id=2;
UPDATE `page_master` SET `page_url`='reports.php' WHERE page_master_id=3;
UPDATE `page_master` SET `page_url`='reports.php?id=2' WHERE page_master_id=4;
UPDATE `page_master` SET `page_url`='reports.php?id=12' WHERE page_master_id=5;
UPDATE `page_master` SET `page_url`='reports.php?id=16' WHERE page_master_id=6;
UPDATE `page_master` SET `page_url`='reports.php?id=15' WHERE page_master_id=7;
UPDATE `page_master` SET `page_url`='reports.php?id=14' WHERE page_master_id=8;
UPDATE `page_master` SET `page_url`='reports.php?id=8' WHERE page_master_id=9;
UPDATE `page_master` SET `page_url`='reports.php?id=6' WHERE page_master_id=10;
UPDATE `page_master` SET `page_url`='reports.php?id=23' WHERE page_master_id=11;
UPDATE `page_master` SET `page_url`='reports.php?id=24' WHERE page_master_id=12;
UPDATE `page_master` SET `page_url`='reports.php?id=26' WHERE page_master_id=13;
UPDATE `page_master` SET `page_url`='reports.php?id=27' WHERE page_master_id=14;
UPDATE `page_master` SET `page_url`='reports.php?id=28' WHERE page_master_id=15;
UPDATE `page_master` SET `page_url`='reports.php?id=32' WHERE page_master_id=16;
UPDATE `page_master` SET `page_url`='reports.php?id=31' WHERE page_master_id=17;
UPDATE `page_master` SET `page_url`='reports.php?id=51' WHERE page_master_id=18;
UPDATE `page_master` SET `page_url`='speed_dashboard.php' WHERE page_master_id=19;
UPDATE `page_master` SET `page_url`='informatics.php' WHERE page_master_id=20;
UPDATE `page_master` SET `page_url`='reports.php?id=40' WHERE page_master_id=21;
UPDATE `page_master` SET `page_url`='exception.php' WHERE page_master_id=22;
UPDATE `page_master` SET `page_url`='vehicle.php' WHERE page_master_id=23;
UPDATE `page_master` SET `page_url`='driver.php' WHERE page_master_id=24;
UPDATE `page_master` SET `page_url`='users.php' WHERE page_master_id=25;
UPDATE `page_master` SET `page_url`='checkpoint.php' WHERE page_master_id=26;
UPDATE `page_master` SET `page_url`='enh_checkpoint.php' WHERE page_master_id=27;
UPDATE `page_master` SET `page_url`='fencing.php' WHERE page_master_id=28;
UPDATE `page_master` SET `page_url`='ecode.php' WHERE page_master_id=29;
UPDATE `page_master` SET `page_url`='route.php' WHERE page_master_id=30;
UPDATE `page_master` SET `page_url`='group.php' WHERE page_master_id=31;
UPDATE `page_master` SET `page_url`='accinfo.php' WHERE page_master_id=32;
UPDATE `page_master` SET `page_url`='accinfo.php?id=3' WHERE page_master_id=33;
UPDATE `page_master` SET `page_url`='support.php' WHERE page_master_id=34;
UPDATE `page_master` SET `page_url`='loginhistory.php' WHERE page_master_id=35;
UPDATE `page_master` SET `page_url`='index.php' WHERE page_master_id=36;  


DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_login_history`$$
CREATE PROCEDURE `insert_login_history`(
	IN pageMasterIdParam int,
	
	IN custno INT,
	IN todaysdate DATETIME,
	IN userid INT,
	OUT logHistoryId int
)
BEGIN
	

	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		/*
		GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
		*/
		ROLLBACK;
	END;
	START TRANSACTION;

	IF(custno = 0) THEN
		SET custno = NULL;
    END IF;
	IF(pageMasterIdParam = 0) THEN
		SET pageMasterIdParam = NULL;
    END IF;

	
		BEGIN
			INSERT INTO login_history_details(
				page_master_id,
				
				customerno,
				created_on,
				created_by
			)VALUES(				
				pageMasterIdParam,
				
				custno,
				todaysdate,
				userid
			);
		END;

		SET logHistoryId = LAST_INSERT_ID();
	COMMIT;
END$$
DELIMITER ;


-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 402;
