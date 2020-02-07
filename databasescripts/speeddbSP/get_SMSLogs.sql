/*
	Name			-	get_SMSLogs
    Description 	-	To get SMS logs for particular customer
    Parameters		-	customernoparam, moduleidparam
    Module			-	Vehicle Tracking System
    Sub-Modules 	- 	Driver Mapping APP
    Sample Call		-	CALL get_SMSLogs(161);
    Created by		-	Mrudang
    Created	on		-	8 Dec, 2015
    Change details 	-
    1) 	Updated by	-	
		Updated	on	-
        Reason		-
    2) 
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `get_SMSLogs`$$
CREATE PROCEDURE `get_SMSLogs`(
    IN customernoparam INT
    , IN moduleidparam INT
,IN dateparam DATETIME  	
)
BEGIN
     
    SELECT	mobileno
			,message
			,response
			,inserted_datetime
	FROM 	smslog
	WHERE 	customerno = customernoparam
    AND 	moduleid = moduleidparam
    AND 	isdeleted = 0
AND  inserted_datetime between dateparam and DATE_ADD(dateparam,INTERVAL 24 HOUR);
    
END$$
DELIMITER ;
