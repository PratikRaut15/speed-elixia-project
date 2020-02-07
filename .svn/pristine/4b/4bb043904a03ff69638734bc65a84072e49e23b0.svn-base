ALTER TABLE leftoverdetails ADD isProcessed TINYINT(1) NOT NULL AFTER daterequired;

DELIMITER $$
DROP PROCEDURE IF EXISTS get_transporter_proposed_indent$$
CREATE PROCEDURE get_transporter_proposed_indent(
	IN custno INT
	, IN propindentid INT
    , IN transid INT
    , IN pitidparam INT
    
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
	IF(propindentid = '' OR propindentid = 0) THEN
		SET propindentid = NULL;
	END IF;
    IF(transid = '' OR transid = 0) THEN
		SET transid = NULL;
	END IF;
    IF(pitidparam = '' OR pitidparam = 0) THEN
		SET pitidparam = NULL;
	END IF;
	SELECT 	        
			pit.pitmappingid	
			,pit.proposedindentid
			,pit.proposed_transporterid
			,pi.hasTransporterAccepted
			,t.transportername
			, pit.proposed_vehicletypeid
			, pit.actual_vehicletypeid
			, vehtype.vehiclecode as proposedvehiclecode
			, veh.vehiclecode as actualvehiclecode
			, pi.date_required
			, depot.depotid
			, depot.depotname
			, factory.factoryid
			, factory.factoryname
			, pit.vehicleno
			, pit.drivermobileno
		    , pit.isAccepted
			, pit.customerno
			, pit.created_on
			, pit.updated_on 
			, pit.created_by
			, pit.updated_by
	FROM 	proposed_indent_transporter_mapping pit
    INNER JOIN proposed_indent pi ON pi.proposedindentid = pit.proposedindentid
    INNER JOIN transporter t ON t.transporterid = pit.proposed_transporterid
    INNER JOIN vehicletype vehtype ON vehtype.vehicletypeid = pit.proposed_vehicletypeid
    left JOIN vehicletype veh ON veh.vehicletypeid = pit.actual_vehicletypeid
    INNER join depot ON depot.depotid = pi.depotid 
    INNER join factory ON factory.factoryid = pi.factoryid
    
	WHERE 	(pit.customerno = custno OR custno IS NULL)
	AND		(pit.proposedindentid = propindentid OR propindentid IS NULL)
    AND		(pit.proposed_transporterid = transid OR transid IS NULL)
    AND		(pit.pitmappingid = pitidparam OR pitidparam IS NULL)
	AND 	pit.isdeleted = 0;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS get_multidepot_load$$ 
CREATE PROCEDURE get_multidepot_load( 
	 IN factoryidparam INT
	, IN multidepotcombination VARCHAR(50)
    , IN custno INT
    , IN reqddate DATETIME
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
    DECLARE multidropdepotid INT DEFAULT 0;
    
    SELECT 	depotid
    INTO 	multidropdepotid
	FROM	multidepot_mapping
	WHERE	factoryid = factoryidparam
    AND		LTRIM(RTRIM(depotmappingid)) = LTRIM(RTRIM(multidepotcombination))
    AND		customerno = custno;
    
    IF (multidropdepotid IS NOT NULL) THEN
		BEGIN
			INSERT INTO factory_delivery( 
										factoryid
										, skuid
										, depotid
										, date_required
										, netWeight
										, grossWeight
										, customerno
										, created_on
										, updated_on 
										, created_by
										, updated_by
									)
			SELECT 		factoryid
						, sku.skuid
                        , multidropdepotid
						, daterequired
						, sum(weight)
                        , (sum(weight) / (1 + netgross))
                        , lod.customerno
                        , todaysdate
                        , todaysdate
                        , userid
                        , userid
            FROM		leftoverdetails AS lod
            INNER JOIN 	leftover_sku_mapping AS lsm ON lod.leftoverid = lsm.leftoverid
            INNER JOIN	sku ON lsm.skuid = sku.skuid
            WHERE		factoryid = factoryidparam
            AND 		FIND_IN_SET(lod.depotid, multidepotcombination)
            AND			isProcessed = 0
            AND			lod.customerno = custno
            GROUP BY 	daterequired, factoryid, depotid;
            
            /* UPDATE the above entries as processed in SKU left over details*/
            
            UPDATE	leftoverdetails AS lod
            SET 	isProcessed = 1
					, updated_on = todaysdate
                    , updated_by = userid
			WHERE	factoryid = factoryidparam
            AND 	FIND_IN_SET(lod.depotid, multidepotcombination)
            AND		daterequired = reqddate
			AND		customerno = custno;
            
		END;
	END IF;
    
    
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS get_left_over_details$$
CREATE PROCEDURE get_left_over_details(
        IN custno INT
        ,IN factoryidparam INT
        ,IN daterequiredparam date
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    IF(factoryidparam = '' OR factoryidparam = 0) THEN
		SET factoryidparam = NULL;
    END IF;
    IF(daterequiredparam = '' OR daterequiredparam = 0) THEN
		SET daterequiredparam = NULL;
    END IF;
    
    SELECT 	
            ld.leftoverid
            , f.factoryid
            , f.factoryname
            , d.depotid
            , d.depotname
            , ld.daterequired
            , ld.weight
            , ld.volume
            , ld.customerno
    FROM    leftoverdetails ld
    INNER JOIN factory f ON f.factoryid = ld.factoryid
    INNER JOIN depot d ON d.depotid = ld.depotid
    WHERE (ld.customerno = custno OR custno IS NULL)
    AND   (ld.factoryid = factoryidparam OR factoryidparam IS NULL)
    AND   (DATE(ld.daterequired) = daterequiredparam OR daterequiredparam IS NULL)
	AND   ld.isProcessed = 0
    AND   ld.isdeleted = 0;
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `assign_proposed_indent_to_next_transporter`$$
CREATE  PROCEDURE `assign_proposed_indent_to_next_transporter`( 
	IN propIndentIdParam INT
    , IN propTransIdParam INT
    , IN propVehTypeIdParam INT
    , IN factoryIdParam INT
    , IN depotIdParam INT
    , IN custno INT
	, IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
    DECLARE zid INT;
    DECLARE temp_sharepercent DECIMAL(5,2);
    DECLARE next_proposed_transporter_id INT;
    DECLARE currentpitmappingid INT;
    
    SELECT 		d.zoneid
    INTO 		zid
    FROM 		depot AS d
    INNER JOIN 	zone AS z ON d.zoneid = z.zoneid
    WHERE 		depotid = depotIdParam
	AND			d.customerno = custno
	AND			d.isdeleted = 0;
    
    IF(zid IS NOT NULL) THEN
		BEGIN
			SELECT 	sharepercent
			INTO 	temp_sharepercent
			FROM 	transportershare
			WHERE 	transporterid = propTransIdParam
			AND		factoryid = factoryIdParam
			AND 	zoneid = zid
            AND		customerno = custno
			AND		isdeleted = 0;
            
			IF(temp_sharepercent IS NOT NULL) THEN
				BEGIN
					SELECT 		t.transporterid
					INTO 		next_proposed_transporter_id
					FROM 		transportershare AS ts
					INNER JOIN 	transporter AS t ON t.transporterid = ts.transporterid
					INNER JOIN	vehtypetransmapping vtm ON vtm.transporterid = t.transporterid
					INNER JOIN 	vehicletype AS vt ON vt.vehicletypeid = vtm.vehicletypeid
					WHERE 		ts.sharepercent <= temp_sharepercent
					AND			ts.zoneid = zid
					AND			ts.factoryid = factoryIdParam
					AND			vtm.vehicletypeid = propVehTypeIdParam
                    AND 		t.transporterid != propTransIdParam
					AND			ts.customerno = custno
					AND 		ts.isdeleted = 0
                    AND 		t.isdeleted = 0
                    AND 		vt.isdeleted = 0
                    AND 		vtm.isdeleted = 0
					AND			t.transporterid NOT IN	(	
												SELECT 	proposed_transporterid 
												FROM 	proposed_indent_transporter_mapping
												WHERE 	proposedindentid = propIndentIdParam
												AND		isAccepted = -1
                                                AND 	proposed_transporterid IS NOT NULL
											)
					ORDER BY 	sharepercent DESC 
                    LIMIT 1;
                    
                    /* 
						If there are no transporters with share less than the current transporter,
						assign the proposed indent to highest share transporter provided he has not rejected it earlier.
					*/
					IF (next_proposed_transporter_id IS NULL) THEN
							SELECT 		t.transporterid
							INTO 		next_proposed_transporter_id
							FROM 		transportershare AS ts
							INNER JOIN 	transporter AS t ON t.transporterid = ts.transporterid
							INNER JOIN	vehtypetransmapping vtm ON vtm.transporterid = t.transporterid
							INNER JOIN 	vehicletype AS vt ON vt.vehicletypeid = vtm.vehicletypeid
                            WHERE		ts.zoneid = zid
							AND			ts.factoryid = factoryIdParam
							AND			vtm.vehicletypeid = propVehTypeIdParam
							AND 		t.transporterid != propTransIdParam
							AND			ts.customerno = custno
							AND 		ts.isdeleted = 0
							AND 		t.isdeleted = 0
							AND 		vt.isdeleted = 0
                            AND 		vtm.isdeleted = 0
                            AND			t.transporterid NOT IN	(	
												SELECT 	proposed_transporterid 
												FROM 	proposed_indent_transporter_mapping
												WHERE 	proposedindentid = propIndentIdParam
												AND		isAccepted = -1
                                                AND 	proposed_transporterid IS NOT NULL
											)
                            ORDER BY 	ts.sharepercent DESC
                            LIMIT 1;
					END IF;
                    
					IF (next_proposed_transporter_id IS NOT NULL) THEN
						BEGIN
							START TRANSACTION;
							CALL `insert_pit_mapping`(propIndentIdParam
													, next_proposed_transporter_id
                                                    , propVehTypeIdParam
                                                    , custno
                                                    , todaysdate
                                                    , userid
                                                    , @currentpitmappingid);
							SELECT 	@currentpitmappingid 
                            INTO	currentpitmappingid
                            FROM 	DUAL;
                            
                            IF(currentpitmappingid IS NOT NULL) THEN
								CALL `update_proposed_indent`(propIndentIdParam, NULL, NULL, NULL, NULL, NULL, 0, todaysdate, userid);
								COMMIT;
							ELSE
                                ROLLBACK;
                            END IF;
						END;
                    END IF;
				END;
			END IF;
		END;
	END IF;
END$$
DELIMITER ;

















DELIMITER $$
DROP PROCEDURE IF EXISTS get_multidepot_load$$ 
CREATE PROCEDURE get_multidepot_load( 
	 IN factoryidparam INT
	, IN multidepotcombination VARCHAR(50)
    , IN custno INT
    , IN reqddate DATETIME
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
    DECLARE multidropdepotid INT DEFAULT 0;
    
    SELECT 	depotid
    INTO 	multidropdepotid
	FROM	multidepot_mapping
	WHERE	factoryid = factoryidparam
    AND		LTRIM(RTRIM(depotmappingid)) = LTRIM(RTRIM(multidepotcombination))
    AND		customerno = custno;
    
    IF (multidropdepotid IS NOT NULL AND multidropdepotid != 0 ) THEN
		BEGIN
			INSERT INTO factory_delivery( 
										factoryid
										, skuid
										, depotid
										, date_required
										, grossWeight
                                        , netWeight
										, customerno
										, created_on
										, updated_on 
										, created_by
										, updated_by
									)
			SELECT 		factoryid
						, sku.skuid
                        , multidropdepotid
						, daterequired
						, sum(totalWeight)
                        , (sum(totalWeight) / (1 + netgross))
                        , lod.customerno
                        , todaysdate
                        , todaysdate
                        , userid
                        , userid
            FROM		leftoverdetails AS lod
            INNER JOIN 	leftover_sku_mapping AS lsm ON lod.leftoverid = lsm.leftoverid
            INNER JOIN	sku ON lsm.skuid = sku.skuid
            WHERE		factoryid = factoryidparam
            AND 		FIND_IN_SET(lod.depotid, multidepotcombination)
            AND			isProcessed = 0
            AND			lod.customerno = custno
            GROUP BY 	daterequired, factoryid, depotid;
            
            /* UPDATE the above entries as processed in SKU left over details*/
            
            UPDATE	leftoverdetails AS lod
            SET 	isProcessed = 1
					, updated_on = todaysdate
                    , updated_by = userid
			WHERE	factoryid = factoryidparam
            AND 	FIND_IN_SET(lod.depotid, multidepotcombination)
            AND		daterequired = reqddate
			AND		customerno = custno;
            
		END;
	END IF;
    
    
END$$
DELIMITER ;




