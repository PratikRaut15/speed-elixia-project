DROP table IF EXISTS `forgot_password_request`;
CREATE TABLE `forgot_password_request` (
  `fpassid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `otp` INT NOT NULL,
  `validupto` datetime DEFAULT NULL,
  `isused` tinyint(1) NOT NULL,
  `request_counter` tinyint(4) NOT NULL,
  `created_on` DATETIME,
  `updated_on` DATETIME,
  `isdeleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`fpassid`)
);


DROP TABLE IF EXISTS `user_history`;
CREATE TABLE IF NOT EXISTS `user_history` (
`user_histid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `stateid` int(11) NOT NULL,
  `realname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(150) NOT NULL,
  `role` varchar(50) NOT NULL,
  `roleid` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `lastvisit` datetime NOT NULL,
  `visited` int(11) NOT NULL,
  `userkey` varchar(150) NOT NULL,
  `mess_email` tinyint(1) NOT NULL,
  `mess_sms` tinyint(1) NOT NULL,
  `speed_email` tinyint(1) NOT NULL,
  `speed_sms` tinyint(1) NOT NULL,
  `power_email` tinyint(1) NOT NULL,
  `power_sms` tinyint(1) NOT NULL,
  `tamper_email` tinyint(1) NOT NULL,
  `tamper_sms` tinyint(1) NOT NULL,
  `chk_email` tinyint(1) NOT NULL,
  `chk_sms` tinyint(1) NOT NULL,
  `ac_email` tinyint(1) NOT NULL,
  `ac_sms` tinyint(1) NOT NULL,
  `ignition_email` tinyint(4) NOT NULL,
  `ignition_sms` tinyint(4) NOT NULL,
  `aci_email` tinyint(1) NOT NULL,
  `aci_sms` tinyint(1) NOT NULL,
  `temp_email` tinyint(1) NOT NULL,
  `temp_sms` tinyint(1) NOT NULL,
  `panic_email` tinyint(1) DEFAULT '0',
  `panic_sms` tinyint(1) DEFAULT '0',
  `immob_email` tinyint(1) DEFAULT '0',
  `immob_sms` tinyint(1) DEFAULT '0',
  `door_sms` tinyint(1) DEFAULT '0',
  `door_email` tinyint(1) DEFAULT '0',
  `modifiedby` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `chgpwd` tinyint(4) NOT NULL,
  `chgalert` tinyint(4) NOT NULL,
  `groupid` int(11) NOT NULL,
  `start_alert` time NOT NULL,
  `stop_alert` time NOT NULL DEFAULT '23:59:59',
  `fuel_alert_sms` tinyint(1) NOT NULL,
  `fuel_alert_email` tinyint(1) NOT NULL,
  `fuel_alert_percentage` int(3) NOT NULL DEFAULT '20',
  `lastlogin_android` datetime NOT NULL,
  `heirarchy_id` int(11) NOT NULL,
  `dailyemail` tinyint(1) DEFAULT '0',
  `dailyemail_csv` tinyint(1) DEFAULT '0',
  `harsh_break_sms` tinyint(1) DEFAULT '0',
  `harsh_break_mail` tinyint(1) DEFAULT '0',
  `high_acce_sms` tinyint(1) DEFAULT '0',
  `high_acce_mail` tinyint(1) DEFAULT '0',
  `sharp_turn_sms` tinyint(1) DEFAULT '0',
  `sharp_turn_mail` tinyint(1) DEFAULT '0',
  `towing_sms` tinyint(1) DEFAULT '0',
  `towing_mail` tinyint(1) DEFAULT '0',
  `tempinterval` varchar(10) NOT NULL,
  `igninterval` varchar(10) NOT NULL,
  `speedinterval` varchar(10) NOT NULL,
  `acinterval` varchar(10) NOT NULL,
  `doorinterval` varchar(10) NOT NULL,
  `chkpushandroid` tinyint(1) NOT NULL,
  `chkmanpushandroid` tinyint(1) NOT NULL,
  `gcmid` text NOT NULL,
  `delivery_vehicleid` int(11) NOT NULL DEFAULT '0',
  `insertdate` datetime DEFAULT NULL,
  PRIMARY KEY (`user_histid`)
);

DELIMITER $$
DROP TRIGGER IF EXISTS `before_user_update`$$
CREATE TRIGGER `before_user_update` BEFORE UPDATE ON `user`
 FOR EACH ROW BEGIN
	IF (NEW.`password` <> OLD.`password`) THEN
		BEGIN
			INSERT INTO user_history
			SET userid = OLD.userid
			, customerno = OLD.customerno
			, stateid = OLD.stateid
			, realname = OLD.realname
			, username = OLD.username
			, `password` = OLD.`password`
			, roleid = OLD.roleid
			, email = OLD.email
			, phone = OLD.phone
			, lastvisit = OLD.lastvisit
			, visited = OLD.visited
			, userkey = OLD.userkey
			, mess_email = OLD.mess_email
			, mess_sms = OLD.mess_sms
			, speed_email = OLD.speed_email
			, speed_sms = OLD.speed_sms
			, power_email = OLD.power_email
			, power_sms = OLD.power_sms
			, tamper_email = OLD.tamper_email
			, tamper_sms = OLD.tamper_sms
			, chk_email = OLD.chk_email
			, chk_sms = OLD.chk_sms
			, ac_email = OLD.ac_email
			, ac_sms = OLD.ac_sms
			, ignition_email = OLD.ignition_email
			, aci_email = OLD.aci_email
			, aci_sms = OLD.aci_sms
			, temp_email = OLD.temp_email
			, temp_sms = OLD.temp_sms
			, panic_email = OLD.panic_email
			, panic_sms = OLD.panic_sms
			, immob_email = OLD.immob_email
			, immob_sms = OLD.immob_sms
			, door_sms = OLD.door_sms
			, door_email = OLD.door_email
			, modifiedby = OLD.modifiedby
			, isdeleted = OLD.isdeleted
			, chgpwd = OLD.chgpwd
			, chgalert = OLD.chgalert
			, groupid = OLD.groupid
			, start_alert = OLD.start_alert
			, stop_alert = OLD.stop_alert
			, fuel_alert_sms = OLD.fuel_alert_sms
			, fuel_alert_email = OLD.fuel_alert_email
			, fuel_alert_percentage = OLD.fuel_alert_percentage
			, lastlogin_android = OLD.lastlogin_android
			, heirarchy_id = OLD.heirarchy_id
			, dailyemail = OLD.dailyemail
			, dailyemail_csv = OLD.dailyemail_csv
			, harsh_break_sms = OLD.harsh_break_sms
			, harsh_break_mail = OLD.harsh_break_mail
			, high_acce_sms = OLD.high_acce_sms
			, high_acce_mail = OLD.high_acce_mail
			, sharp_turn_sms = OLD.sharp_turn_sms
			, sharp_turn_mail = OLD.sharp_turn_mail
			, towing_sms = OLD.towing_sms
			, towing_mail = OLD.towing_mail
			, tempinterval = OLD.tempinterval
			, igninterval = OLD.igninterval
			, speedinterval = OLD.speedinterval
			, acinterval = OLD.acinterval
			, doorinterval = OLD.doorinterval
			, chkpushandroid = OLD.chkpushandroid
			, chkmanpushandroid = OLD.chkmanpushandroid
			, delivery_vehicleid = OLD.delivery_vehicleid
			, insertdate = NOW();
		END;
	END IF;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS authenticate_for_login$$
CREATE PROCEDURE authenticate_for_login(
	IN usernameparam VARCHAR(50)
	,IN passparam VARCHAR(150)
	,IN todaydt DATETIME
	,OUT usertype INT
	,OUT userkeyparam INT
)
BEGIN
	DECLARE useridparam INT;
    DECLARE tempPwdParam VARCHAR(150);
	DECLARE del INT;
	/* usertype = 0 : Normal user without forgot password request */
	SET usertype = 0;
	SET userkeyparam = 0;
    
	SELECT  userid
	INTO	useridparam 
	FROM    user
	WHERE   username = usernameparam
	AND 	password = passparam
	AND 	isdeleted = 0;
	
	IF (useridparam IS NULL)THEN            
		BEGIN
			SELECT 	f.userid, u.userkey
            INTO 	useridparam, userkeyparam
			FROM 	forgot_password_request AS f
            INNER JOIN	user AS u ON u.userid = f.userid
			WHERE 	f.username = usernameparam 
			AND 	CAST(SHA1(f.otp) AS BINARY) = CAST(passparam AS BINARY)
			AND 	f.isdeleted = 0
            AND 	u.isdeleted = 0
			AND 	f.isused = 0
			AND 	f.request_counter <= 3
			AND 	f.validupto BETWEEN todaydt AND DATE_ADD(todaydt, INTERVAL 24 HOUR);
		END;
		IF (useridparam IS NOT NULL) THEN
			BEGIN
				/* usertype = 1: User who has raised forgot password request and is validated */
				SET usertype = 1;
			END; 
		END IF;
	END IF;

	IF (useridparam IS NOT NULL AND usertype = 0)THEN 
		BEGIN
			CALL check_grpdel(useridparam,@isdeleted);
			SELECT @isdeleted INTO del;
			IF (del = 0)THEN 
				BEGIN
					SELECT  	*
					FROM		user
                    INNER JOIN 	customer ON user.customerno = customer.customerno
                    INNER JOIN 	android_version
					WHERE   	userid = useridparam AND isdeleted =0;
				END;
			END IF;
		END;
	END IF;

END$$
DELIMITER ;
------------------------------
DELIMITER $$
DROP PROCEDURE IF EXISTS `check_grpdel`$$
CREATE PROCEDURE `check_grpdel`(
	IN useridparam INT
	,OUT isdelparam INT
)
BEGIN
	DECLARE groupidparam INT;
	DECLARE group_count INT;
    
	SELECT  groupid
	INTO 	groupidparam 
	FROM    user
	WHERE  	user.userid = useridparam AND user.isdeleted = 0;

	IF (groupidparam = 0)THEN
		BEGIN 
			SET isdelparam = 0;
		END;
	ELSEIF (groupidparam != 0) THEN
		BEGIN 
			SELECT 	COUNT(groupid) INTO group_count 
            FROM 	`groupman`
            WHERE 	groupman.userid = useridparam 
            AND 	groupman.isdeleted=0;
            
			IF(group_count = 0)THEN
				BEGIN
					SET isdelparam = 1;
				END;
			ELSE 
				BEGIN
					SET isdelparam = 0;
				END;
			END IF;
		END;
	ELSE 
		SET isdelparam = 1;
	END IF;
END$$
DELIMITER ;


--------------------------

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_forgot_password_request`$$
CREATE PROCEDURE `insert_forgot_password_request`(
	IN useridparam INT
	, IN todaysdate datetime
	, OUT otpparam INT
    , OUT otpvaliduptoparam DATETIME
)
BEGIN
	DECLARE request_counterparam INT;
	DECLARE username_param varchar(50);
	DECLARE phone_param varchar(15);
    DECLARE minotp INT DEFAULT 100000;
    DECLARE maxotp INT DEFAULT 999999;
    
	IF EXISTS (	SELECT 	userid 
				FROM 	forgot_password_request  
				WHERE 	userid = useridparam 
				AND 	isused = 0 
				AND 	isdeleted = 0
				AND 	validupto BETWEEN todaysdate AND DATE_ADD(todaysdate,INTERVAL 24 HOUR) 
				LIMIT 1) THEN 
		BEGIN
			SELECT 	otp,request_counter, validupto 
			INTO 	otpparam, request_counterparam, otpvaliduptoparam
			FROM 	forgot_password_request 
			WHERE 	userid = useridparam 
			AND 	isused = 0 
			AND 	isdeleted = 0
			AND 	validupto between todaysdate AND DATE_ADD(todaysdate,INTERVAL 24 HOUR) LIMIT 1;
            
			IF(request_counterparam = 3) THEN
				SET otpparam = -1; 
			ELSE
				UPDATE 	forgot_password_request 
				SET 	request_counter = request_counterparam + 1 
						, updated_on = todaysdate
				WHERE 	userid = useridparam 
				AND 	isused = 0 
				AND 	isdeleted = 0
				AND 	validupto BETWEEN todaysdate AND DATE_ADD(todaysdate,INTERVAL 24 HOUR);
			END IF;
		END;
	ELSE
		BEGIN
			SELECT 	phone, username 
			INTO 	phone_param, username_param  
			FROM 	user 
			WHERE 	userid = useridparam 
			AND 	isdeleted = 0;
            
            SET otpparam = FLOOR(RAND() * (maxotp - minotp + 1)) + minotp;
            SET otpvaliduptoparam = DATE_ADD(todaysdate,INTERVAL 24 HOUR);
			
			INSERT INTO forgot_password_request (
						  userid
						, username
						, phone
						, otp
						, validupto
						, isused
						, request_counter
                        , created_on
                        , updated_on
						) 
					VALUES	(
						 useridparam
						, username_param
						, phone_param
						, otpparam
						, otpvaliduptoparam
						, 0
						, 1
                        , todaysdate
                        , todaysdate
					);
		END;
	END IF;

END$$
DELIMITER ;
--------------------------------------------------------

DELIMITER $$
DROP PROCEDURE IF EXISTS `speed_forgot_password`$$
CREATE PROCEDURE `speed_forgot_password`(
 IN usernameparam VARCHAR(50)
, IN todaysdate datetime
, OUT userexists TINYINT
)
BEGIN
	DECLARE otpparam INT;
	DECLARE otpvalidupto DATETIME;
    DECLARE useremail VARCHAR(50);
    DECLARE userphone VARCHAR(15);
    DECLARE custno INT;
	DECLARE useridparam INT;
    
	SELECT 	userid, COALESCE(email, username, ''), COALESCE(phone, ''), customerno
    INTO 	useridparam, useremail, userphone, custno
    FROM 	user
    WHERE 	(username=usernameparam OR email=usernameparam)
    AND 	isdeleted = 0;
    
	IF (useridparam IS NOT NULL)THEN
		CALL insert_forgot_password_request(useridparam, todaysdate, @otpparam, @otpvaliduptoparam);
		SELECT @otpparam, @otpvaliduptoparam INTO otpparam, otpvalidupto;
        
        SELECT 	useridparam
				, otpparam
				, otpvalidupto
                , useremail
                , userphone
                , custno;
                
		SET userexists = 1;
	ELSE
		SET userexists = 0;
	END IF;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `update_newforgotpassword`$$
CREATE PROCEDURE `update_newforgotpassword`(
	IN newpwdparam varchar(150)
	, IN userkeyparam VARCHAR(150)
    , IN todaysdate DATETIME
)
BEGIN
	DECLARE tempUserId INT;
    DECLARE tempForgotPwdUserId INT;
    SELECT 	userid
    INTO	tempUserId
    FROM	user
    WHERE	userkey = userkeyparam;
    
	IF (tempUserId IS NOT NULL) THEN
		SELECT 	userid
        INTO	tempForgotPwdUserId
        FROM	forgot_password_request
        WHERE	userid = tempUserId
        AND 	isused = 0
		AND 	isdeleted = 0
        AND 	request_counter <= 3
        AND 	validupto BETWEEN todaysdate AND DATE_ADD(todaysdate,INTERVAL 24 HOUR);
        
        IF (tempForgotPwdUserId IS NOT NULL) THEN
			BEGIN
				UPDATE 	user 
				SET 	chgpwd = 1
						, password = newpwdparam
				WHERE 	userid = tempUserId;

				UPDATE 	forgot_password_request
				SET 	isused = 1
						, updated_on = todaysdate
				WHERE 	userid = tempUserId
				AND 	isdeleted = 0
				AND 	isused = 0;
			END;
        END IF;
	END IF;
END$$

DELIMITER ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (323, NOW(), 'Mrudang Vora','Speed Forgot Password DB Changes');