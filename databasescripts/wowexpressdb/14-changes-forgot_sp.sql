DELIMITER $$
DROP PROCEDURE IF EXISTS checkloginrequest$$
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
    SET useridparam = NULL;
	IF (oauthuseridparam = '' ) THEN
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
			AND 	CAST(SHA1(otp) AS BINARY) = CAST(pwdparam AS BINARY)
			AND 	isdeleted = 0 
			AND 	isused = 0
			AND 	request_counter <= 3
			AND 	validupto BETWEEN todaydt AND DATE_ADD(todaydt,INTERVAL 24 HOUR);
            
            IF(useridparam IS NOT NULL) THEN
				/* usertype = 1: User who has raised forgot password request and is validated */
				SET usertype = 1;
            END IF;
		END;
	END IF;
	
	IF (useridparam IS NOT NULL AND usertype = 0)THEN
            SELECT * FROM user WHERE userid = useridparam;
	END IF;
    
    

END$$
DELIMITER ;

---------------------------------------------------------------------------------



DELIMITER $$
DROP PROCEDURE IF EXISTS `wow_insert_forgot_password_request`$$
CREATE PROCEDURE `wow_insert_forgot_password_request`(
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
			SELECT 	phone, `email` 
			INTO 	phone_param, username_param  
			FROM 	user 
			WHERE 	userid = useridparam 
			AND 	approved = 1;
            
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

---------------------------------------------------------------------------------------------

DELIMITER $$
DROP PROCEDURE IF EXISTS `update_newforgotpassword`$$
CREATE PROCEDURE `update_newforgotpassword`(
	IN newpwdparam varchar(150)
	, IN useridparam VARCHAR(150)
    , IN todaysdate DATETIME
)
BEGIN
	DECLARE tempUserId INT;
    DECLARE tempForgotPwdUserId INT;
    
    SELECT 	userid
    INTO	tempUserId
    FROM	user
    WHERE	userid = useridparam;
    
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
				SET 	`password` = newpwdparam
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
-----------------------------------------------------------------------------------

DELIMITER $$
DROP PROCEDURE IF EXISTS `wow_forgot_password`$$
CREATE PROCEDURE `wow_forgot_password`(
 IN usernameparam VARCHAR(50)
, IN todaysdate datetime
, OUT userexists TINYINT
)
BEGIN
	DECLARE otpparam INT;
	DECLARE otpvalidupto DATETIME;
    DECLARE useremail VARCHAR(50);
    DECLARE userphone VARCHAR(15);
    DECLARE useridparam INT;
    
	SELECT 	userid, COALESCE(email, `name`, ''), COALESCE(phone, '')
    INTO 	useridparam, useremail, userphone
    FROM 	user
    WHERE 	(`name`=usernameparam OR email=usernameparam)
    AND 	isdeleted = 0;
    
	IF (useridparam IS NOT NULL)THEN
		CALL wow_insert_forgot_password_request(useridparam, todaysdate, @otpparam, @otpvaliduptoparam);
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


 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (14, NOW(), 'Ganesh','Wow Forgot Password');


