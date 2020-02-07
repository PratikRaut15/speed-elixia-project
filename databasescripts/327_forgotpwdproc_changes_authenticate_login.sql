-------------------------proc changes----------------------------------
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

	/* usertype = 0 : Normal user without forgot password request */
	SET usertype = 0;
	SET userkeyparam = 0;
    
	SELECT  userid,userkey
	INTO	useridparam , userkeyparam
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
			
					SELECT  	*
					FROM		user
                    INNER JOIN 	customer ON user.customerno = customer.customerno
                    INNER JOIN 	android_version
					WHERE   	userid = useridparam AND isdeleted =0;
				END;
	END IF;

END$$
DELIMITER ;

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (327, NOW(), 'Sahil','Speed Forgot Password SP Change in Authenticate Login');
