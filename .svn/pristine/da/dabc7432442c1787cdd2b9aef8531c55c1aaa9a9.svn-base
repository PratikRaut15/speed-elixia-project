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