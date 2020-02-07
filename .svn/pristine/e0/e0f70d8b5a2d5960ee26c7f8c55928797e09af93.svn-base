USE TMS;
/*
    Name			-	insert_transportershare
    Description 	-	To insert share of particular transporter
    Parameters		-	transporterid, zoneid, sharepercent,customerno,todaysdate,userid,currenttransportershareid
    Module			-	TMS
    Sub-Modules 	- 	Transporter Share - Add
    Sample Call		-	CALL insert_transportershare(1,1,2,20,116,'2015-12-08 00:00:00',1074,@currenttransportershareid);
						SELECT @currenttransportershareid;
    Created by		-	Mrudang
    Created on		- 	8 Dec, 2015
    Change details 	-	
    1) 	Updated by	- 	Mrudang
		Updated	on	- 	15 Dec, 2015
        Reason		-	Adding new share in actual share table with default 0 shared weight.
    2)  
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_transportershare`$$
CREATE PROCEDURE `insert_transportershare`( 
	IN transporteridparam INT
	, IN factoryidparam INT
	, IN zoneidparam INT
    , IN sharepercentparam decimal(6, 2)
	, IN custnoparam INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currenttransportershareid INT
	)
BEGIN
    DECLARE transportercount INT;
    DECLARE total_weightparam DECIMAL(11,3);
	INSERT INTO transportershare(
							transporterid
                            , factoryid
							, zoneid
                            , sharepercent
                            , customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						)
	VALUES ( 
				transporteridparam
                , factoryidparam
				, zoneidparam
                , sharepercentparam
				, custnoparam
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
    SET 	transportercount = 0;
    SET 	total_weightparam = 0;
    
	SELECT 	count(transporterid) 
    INTO	transportercount 
    FROM	transporter_actualshare
    WHERE	factoryid = factoryidparam
    AND 	zoneid = zoneidparam
	AND		customerno = custnoparam
    AND 	isdeleted = 0;
    
    
    IF(transportercount > 0) THEN
		BEGIN
			SELECT 	total_weight
            INTO 	total_weightparam
            FROM	transporter_actualshare
			WHERE	factoryid = factoryidparam
			AND 	zoneid = zoneidparam
            AND		customerno = custnoparam
			AND 	isdeleted = 0 
			LIMIT 1;
            SET sharepercentparam = 0;
        END;
    END IF;
    
	CALL insert_transporteractualshare(transporteridparam, factoryidparam, zoneidparam, total_weightparam, sharepercentparam, custnoparam, todaysdate, userid);
	SET currenttransportershareid = LAST_INSERT_ID();
END$$
DELIMITER ;
