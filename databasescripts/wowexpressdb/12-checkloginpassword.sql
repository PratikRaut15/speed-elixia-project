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


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (12, NOW(), 'Ganesh Papde','otp password check');

