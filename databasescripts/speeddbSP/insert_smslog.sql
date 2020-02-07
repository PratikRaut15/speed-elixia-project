/*
	Name			-	insert_smslog
    Description 	-	To insert SMS logs for particular customer
    Parameters		-	As listed below
    Module			-	Vehicle Tracking System
    Sub-Modules 	- 	None
    Sample Call		-	CALL insert_smslog('9970442311','Vehicle No: MH 04 EF 4655 Location: Near B-3/118, Rajawadi Colony, Ghatkopar East, Mumbai, Maharashtra 400077, India Shared by: Elixir','','443','4','2','1','2016-01-13 17:48:44','4',@smsid);
						SELECT @smsid;
    Created by		-	Mrudang
    Created	on		-	13 Jan, 2016
    Change details 	-
    1) 	Updated by	-	Mrudang Vora
		Updated	on	-	13 Jan, 2016
        Reason		-	Added moduleid
    2) 
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS insert_smslog$$
CREATE PROCEDURE `insert_smslog`(
IN mobilenoparam VARCHAR(10)
, IN messageparam VARCHAR(250)
, IN responseparam VARCHAR(250)
, IN vehicleidparam INT
, IN useridparam INT
, IN customernoparam INT
, IN isSmsSentParam TINYINT(1)
, IN todaysdate DATETIME
, IN moduleidparam TINYINT(1)
, IN cqidParam INT(11)
, OUT smsid INT
)
BEGIN

INSERT INTO smslog (
                    mobileno
                    , message
                    , response
                    , vehicleid
                    , userid
                    , moduleid	
                    , customerno
                    , issmssent
                    , inserted_datetime
                    , cqid
                    ) 
		VALUES (
                    mobilenoparam
                    , messageparam
                    , responseparam
                    , vehicleidparam
                    , useridparam
                    , moduleidparam
                    , customernoparam
                    , isSmsSentParam
                    , todaysdate
                    , cqidParam
                );
SET smsid = LAST_INSERT_ID();
END$$
DELIMITER ;