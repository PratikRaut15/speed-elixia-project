
DROP table IF EXISTS `forgot_password_request`;
CREATE TABLE `forgot_password_request` (
  `fpassid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `otp` varchar(150) NOT NULL,
  `validupto` datetime DEFAULT NULL,
  `isused` tinyint(1) NOT NULL,
  `request_counter` tinyint(4) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`fpassid`)
);

--------------------------------------------------------------
DELIMITER $$
DROP PROCEDURE IF EXISTS `checkloginrequest`$$
CREATE PROCEDURE `checkloginrequest`(
IN usernameparam varchar(150)
,IN pwdparam VARCHAR(150)
,IN todaydt DATETIME
,IN oauthuseridparam varchar(100)
,OUT usertype INT
,OUT useridparam INT
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
	
	IF (useridparam IS NOT NULL AND usertype = 0)THEN
            SELECT * FROM user WHERE userid = useridparam;
	END IF;
    
    

END $$
DELIMITER ;

-----------------------------------------------------------------------------
DELIMITER $$
DROP PROCEDURE IF EXISTS `wow_forgot_api_request`$$
CREATE PROCEDURE `wow_forgot_api_request`(
IN useridparam INT,
IN todaysdate datetime,
OUT otpparam INT
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
			SELECT 	otp,request_counter 
			INTO 	otpparam, request_counterparam  
			FROM 	forgot_password_request 
			WHERE 	userid = useridparam 
			AND 	isused=0 
			AND 	isdeleted=0
			AND 	validupto between todaysdate AND DATE_ADD(todaysdate,INTERVAL 24 HOUR) LIMIT 1;
            
			IF(request_counterparam = 3) THEN
				SET otpparam = -1; 
			ELSE
				UPDATE 	forgot_password_request 
				SET 	request_counter = request_counterparam + 1 
				WHERE 	userid = useridparam 
				AND 	isused = 0 
				AND 	isdeleted = 0
				AND 	validupto BETWEEN todaysdate AND DATE_ADD(todaysdate,INTERVAL 24 HOUR);
			END IF;
		END;
	ELSE
		BEGIN
			SELECT 	phone, email 
			INTO 	phone_param, username_param  
			FROM 	user 
			WHERE 	userid = useridparam 
			AND 	approved = 1;
            
            SET otpparam = FLOOR(RAND() * (maxotp - minotp + 1)) + minotp;
			
			INSERT INTO forgot_password_request (
						  userid
						, username
						, phone
						, otp
						, validupto
						, isused
						, request_counter
						) 
					VALUES	(
						 useridparam
						, username_param
						, phone_param
						, sha1(otpparam) 
						, DATE_ADD(todaysdate,INTERVAL 24 HOUR)
						, 0
						, 1
					);
		END;
	END IF;
END $$
DELIMITER ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (10, NOW(), 'Ganesh Papde','forgotlogin sp or create new table');


