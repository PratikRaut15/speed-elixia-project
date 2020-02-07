DROP TABLE IF EXISTS `user_history`;
CREATE TABLE `user_history` (
`user_histid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11),
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `device_id` varchar(75) NOT NULL,
  `gcmid` text NOT NULL,
  `platform` int(4) NOT NULL,
  `otp` int(4) NOT NULL,
  `password` varchar(50) NOT NULL,
  `oauthuserid` varchar(50) NOT NULL,
  `createdon` datetime NOT NULL,
  `updatedon` datetime NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `insertdate` datetime DEFAULT NULL,
  PRIMARY KEY (`user_histid`)
);


DELIMITER $$
DROP TRIGGER IF EXISTS `before_user_update`$$
CREATE TRIGGER `before_user_update` BEFORE UPDATE ON `user`
 FOR EACH ROW BEGIN
	IF (NEW.`password` <> OLD.`password` OR NEW.`gcmid` <> OLD.`gcmid` OR NEW.oauthuserid  <> OLD.oauthuserid) THEN
		BEGIN
			INSERT INTO user_history
			SET userid = OLD.userid
			, name = OLD.name
			, email = OLD.email
			, phone = OLD.phone
            , device_id = OLD.device_id
            , gcmid = OLD.gcmid
            , platform = OLD.platform
            , otp = OLD.otp
			, `password` = OLD.`password`
            , oauthuserid = OLD.oauthuserid
            , createdon = OLD.createdon
            , updatedon = OLD.updatedon
            , approved = OLD.approved
			, insertdate = NOW();
		END;
	END IF;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS checkloginrequest$$
CREATE PROCEDURE `checkloginrequest`(
 IN usernameparam varchar(150)
, IN pwdparam VARCHAR(150)
, IN todaydt DATETIME
, IN oauthuseridparam varchar(100)
, IN gcmidparam TEXT
, OUT usertype INT
, OUT useridparam INT
)
BEGIN
	/* usertype = 0 : Normal user without forgot password request */
	SET usertype = 0;
	IF (oauthuseridparam = '') THEN
		SET oauthuseridparam = NULL;
    END IF;
	IF(oauthuseridparam IS NULL) THEN 
		BEGIN
			SELECT  userid 
			INTO    useridparam 
			FROM    `user` 
			WHERE   `email`= usernameparam 
			AND     `password`= pwdparam 
			AND    	approved = 1;
		END;
	ELSE
		BEGIN
			SELECT	userid 
			INTO    useridparam 
			FROM    `user` 
			WHERE   `email`= usernameparam 
			AND 	`oauthuserid`= oauthuseridparam 
			AND    	approved = 1;
		END;
	END IF;

	IF (useridparam IS NULL AND oauthuseridparam IS NULL)THEN            
		BEGIN
			SELECT 	userid 
            INTO 	useridparam  
			FROM 	forgot_password_request 
			WHERE 	`username`= usernameparam 
			AND 	`otp`= pwdparam 
			AND 	isdeleted = 0 
			AND 	isused = 0
			AND 	request_counter <= 3
			AND 	validupto < todaydt;
            
            IF(useridparam IS NOT NULL) THEN
				/* usertype = 1: User who has raised forgot password request and is validated */
				SET usertype = 1;
            END IF;
		END;
	END IF;
	
	IF (useridparam IS NOT NULL AND usertype = 0) THEN
			/* Need to update gcm id everytime user logs in as he might log in from different devices. */
			UPDATE user SET gcmid = gcmidparam WHERE userid = useridparam;
            SELECT * FROM user WHERE userid = useridparam;
	END IF;

END$$
DELIMITER ;


 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (15, NOW(), 'Mrudang','Add Gcmid for Login');


