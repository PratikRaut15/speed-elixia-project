/*
    Name			-	event_ResetActualShare
    Description 	-	To reset actual share table on monthly basis.
    Parameters		-	None
    Module			-	TMS
    Sub-Modules 	- 	
    Sample Call		-	CALL eventsp_ResetActualShare();
    Created by		-	Mrudang
    Created on		- 	16 Jan, 2016
    Change details 	-	
    1) 	Updated by	- 	
		Updated	on	- 	
        Reason		-	
    2)  Updated by	- 	
		Updated	on	- 	
        Reason		-
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS event_ResetActualShare$$
CREATE PROCEDURE `eventsp_ResetActualShare`(
)
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		-- ERROR
		ROLLBACK;
	END;
	START TRANSACTION;
	UPDATE  transporter_actualshare 
	SET     shared_weight = 0
			,total_weight = 0;
	
	UPDATE transporter_actualshare tas 
	INNER JOIN transportershare ts 
    ON     ts.transporterid = tas.transporterid
	AND    ts.factoryid = tas.factoryid
	AND    ts.zoneid    =    tas.zoneid
	AND    ts.customerno = tas.customerno
	AND    ts.isdeleted = 0
	SET    tas.actualpercent = ts.sharepercent
	WHERE  tas.actualpercent != ts.sharepercent;
	COMMIT;
END$$
DELIMITER ;