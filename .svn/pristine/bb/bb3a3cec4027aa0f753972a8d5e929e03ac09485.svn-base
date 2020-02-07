USE TMS;
/*
    Name			-	insert_transporteractualshare
    Description 	-	To insert share of particular transporter in actual share table
    Parameters		-	transporterid, zoneid, sharepercent,customerno,todaysdate,userid,currenttransportershareid
    Module			-	TMS
    Sub-Modules 	- 	Transporter Share - Add (called internally from insert_transportershare SP
    Sample Call		-	CALL insert_transporteractualshare(1, 1, 2, 0, 0, 116, '2015-12-08 00:00:00',1074);
    Created by		-	Mrudang
    Created on		- 	15 Dec, 2015
    Change details 	-	
    1) 	Updated by	- 	Mrudang
		Updated	on	- 	15 Dec, 2015
        Reason		-	Adding new share in actual share table with default 0 shared weight.
    2)  
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_transporteractualshare`$$
CREATE PROCEDURE `insert_transporteractualshare`( 
	IN transporterid INT
	, IN factoryid INT
	, IN zoneid INT
    , IN total_weight DECIMAL(11,3)
	, IN sharepercent decimal(6, 2)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	INSERT INTO `transporter_actualshare`(
						`factoryid`
						, `zoneid`
						, `transporterid`
                        , `total_weight`
						, `actualpercent`
						, `customerno`
						, `created_on`
						, `updated_on`
						, `created_by`
						, `updated_by`) 
				VALUES (factoryid
					, zoneid
					, transporterid
                    , total_weight
                    , sharepercent
					, customerno
					, todaysdate
					, todaysdate
					, userid
					, userid
					);

END$$
DELIMITER ;
