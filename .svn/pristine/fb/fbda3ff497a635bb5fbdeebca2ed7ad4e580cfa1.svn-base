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



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 7, NOW(), 'Mrudang Vora','Transporter Share Changes')