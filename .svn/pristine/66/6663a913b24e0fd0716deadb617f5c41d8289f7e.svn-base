INSERT INTO speed.dbpatches (patchid, patchdate, appliedby, patchdesc, isapplied) 
VALUES ('528', '2017-09-23 16:40:18', 'Mrudang Vora', 'Elixia SMS', '0');

DROP TABLE IF EXISTS elixiaSms;
CREATE TABLE elixiaSms (
	elixiaSmsId INT NOT NULL AUTO_INCREMENT
	,userPhoneNo VARCHAR(10) NOT NULL
	,message VARCHAR(10) NOT NULL
	,requestedOn DATETIME
    ,userId INT
	,customerno INT
	,createdOn DATETIME
    ,isProcessed TINYINT(1) NOT NULL DEFAULT 0
	,processedOn DATETIME
	,isDeleted TINYINT(1)
	,PRIMARY KEY(elixiaSmsId)
);


/*
	Name			-	elixiasms_insert_request
	Description 	-
	Parameters		-
	Module			-	Speed
	Sub-Modules 	- 	ElixiaSMS
	Sample Call		-	call elixiasms_insert_request(9969941084, 'IGN', '2017-09-08 15:51:54', 123, 2, '2017-09-08 15:55:54', @insertedId);
						SELECT @insertedId;
	Created by		-	Mrudang Vora
	Created on		- 	23 Sep, 2017
	Change details 	-
	1) 	Updated by	-
		Updated	on	-
		Reason		-
	2)
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS elixiasms_insert_request$$
CREATE PROCEDURE elixiasms_insert_request(
	 IN phoneNoParam VARCHAR(10)
	, IN messageParam VARCHAR(10)
	, IN requestedOnParam DATETIME
    , IN userIdParam INT
    , IN custnoParam INT    
	, IN todaysDate DATETIME
	, OUT insertedId INT
)
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		ROLLBACK;
		/*
		GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
		@errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
		SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
		SELECT @full_error;
		*/
		SET insertedId = 0;
	END;
    START TRANSACTION;
		BEGIN
			INSERT INTO speed.elixiaSms
			(
				userPhoneNo
				,message
				,requestedOn
                ,userId
				,customerno
				,createdOn
			)
			VALUES
			(
				phoneNoParam
                ,messageParam
                ,requestedOnParam
                ,userIdParam
                ,custnoParam
                ,todaysDate
            );
            SET insertedId = LAST_INSERT_ID();
        END;
	COMMIT;
END$$
DELIMITER ;
UPDATE speed.dbpatches SET isapplied=1 WHERE patchid = 528;