/*
    Name          - get_dbpatchNo
    Description   - Get latest patch id
    Parameters    - latest_patchid,patchdesc,appliedby,updated_date

    Module    - SPEED
    Sample Call   -CALL get_dbpatchNo('Enter Patch Description', 'Manasvi Thakur')

    Created by    - Manasvi Thakur
    Created on    - 16-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS get_dbpatchNo$$
CREATE PROCEDURE `get_dbpatchNo`(
IN patchdesc varchar(50),
IN appliedby varchar(20),
OUT currentDBpatch INT
)
BEGIN
    DECLARE latestpatchid INT;
	DECLARE existDBpactId INT;
    DECLARE  istDateTime INT;
    DECLARE  serverTime INT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    SET @serverTime = now();
    SET @istDateTime = CONVERT_TZ(@serverTime, 'SYSTEM', '+05:30');
    BEGIN
        ROLLBACK;
	/* 	
       GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
       @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
       SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
       SELECT @full_error;
*/       

    END;
    set currentDBpatch = 0;
		SELECT patchid INTO existDBpactId FROM dbpatches
		ORDER BY patchid DESC LIMIT 1
        ;
	
	SET latestpatchid = existDBpactId + 1;
   
	BEGIN
		INSERT INTO dbpatches(
			patchid,
			patchdate,
			appliedby,
			patchdesc,
			isapplied
		)VALUES(
		  latestpatchid,	
		   @serverTime,
		   appliedby,
		   patchdesc,
			0	
			);
	set currentDBpatch = latestpatchid;
	END;
		
	BEGIN
			 UPDATE dbpatches
			 SET
				 isapplied = 1,
				 updatedOn = @serverTime
			WHERE patchid = latestpatchid;
	END;

END$$
DELIMITER ;
 -- call get_dbpatchNo('Enter Patch Description', 'Manasvi Thakur',@currentDBpatch);
 -- select @currentDBpatch;
