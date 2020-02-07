ALTER TABLE `user` ADD `isdeleted` TINYINT(1) NOT NULL DEFAULT '0' ;
ALTER TABLE `user_history` ADD `isdeleted` TINYINT(1) NOT NULL DEFAULT '0' AFTER `approved`;


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
            , isdeleted = OLD.isdeleted
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
			AND     CAST(SHA1(otp) AS BINARY) = CAST(pwdparam AS BINARY)
			AND 	isdeleted = 0 
			AND 	isused = 0
			AND 	request_counter <= 3
			AND 	validupto BETWEEN todaydt AND DATE_ADD(todaydt, INTERVAL 24 HOUR);
            
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
 VALUES (16, NOW(), 'Mrudang','Added isdeleted field and corrected the validupto in checkloginrequest SP');