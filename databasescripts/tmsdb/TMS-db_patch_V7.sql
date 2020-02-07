ALTER TABLE `factory_delivery` ADD `netWeight` DECIMAL(7,3) NOT NULL AFTER `date_required`;
ALTER TABLE `factory_delivery` CHANGE `weight` `grossWeight` DECIMAL(7,3) NOT NULL;

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_skuweight_byfactorydepot`$$
CREATE  PROCEDURE `get_skuweight_byfactorydepot`(
    IN custno INT
    , IN datereq datetime
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
        SET custno = NULL;
    END IF;
    IF(datereq = '' OR datereq LIKE '%0000-00-00%') THEN
        SET datereq = NULL;
    END IF;
    SELECT     sum(fd.grossWeight) as weight
            , fd.date_required
            , fd.factoryid
            , fd.depotid
	FROM 	factory_delivery fd
	WHERE   (customerno = custno OR custno IS NULL)
	AND     (fd.date_required = datereq OR datereq IS NULL)
	AND 	(fd.isProcessed=0)
	GROUP BY date_required, factoryid, depotid;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_factory_delivery`$$
CREATE PROCEDURE `insert_factory_delivery`( 
	IN factoryid int
	, IN skuidparam int
	, IN depotid int
	, IN date_required date
	, IN weight decimal(7,3)
	, IN custno INT
    , IN todaysdate DATETIME
    , IN userid INT
	, OUT currentfactorydeliveryid INT
)
BEGIN
	DECLARE grosswt decimal(7,3);
    DECLARE tempnetgross decimal(5,2);
    
    SELECT  netgross
	INTO 	tempnetgross
    FROM 	sku
    WHERE 	skuid = skuidparam
    AND 	customerno = custno;
    
    SET grosswt = weight + (weight * COALESCE(tempnetgross,0));
    
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
	VALUES ( 
				factoryid 
                , skuidparam 
                , depotid
                , date_required
                , weight
                , grosswt
                , custno
                , todaysdate
                , todaysdate
                , userid
                , userid
			);

	SET currentfactorydeliveryid = LAST_INSERT_ID();

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_skuweight`$$
CREATE PROCEDURE `get_skuweight`(
    IN custno INT
    , IN did INT
    , IN factid INT
    , IN datereq datetime
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
        SET custno = NULL;
    END IF;
    IF(did = '' OR did = 0) THEN
        SET did = NULL;
    END IF;
    IF(factid = '' OR factid = 0) THEN
        SET factid = NULL;
    END IF;
    IF(datereq = '' OR datereq LIKE '%0000-00-00%') THEN
        SET datereq = NULL;
    END IF;
    SELECT  fd.skuid
			, s.skucode
			, s.sku_description
			, s.skutypeid
			, s.weight as unitweight
            , s.volume as unitvolume
            , sum(fd.grossWeight) as skuweight
            , fd.date_required
            , fd.depotid
            , fd.factoryid
    FROM     factory_delivery fd INNER JOIN sku s ON fd.skuid = s.skuid
    WHERE    (fd.customerno = custno OR custno IS NULL)
    AND      (fd.factoryid = factid OR factid IS NULL)
    AND      (fd.depotid = did OR did IS NULL)
    AND      (fd.date_required = datereq OR datereq IS NULL)
    AND      (fd.isProcessed=0)
    GROUP BY date_required,factoryid, depotid, skuid;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_factory_delivery`$$
CREATE PROCEDURE `get_factory_delivery`(
	IN custno INT
    , IN fdidparam INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(fdidparam = '' OR fdidparam = 0) THEN
		SET fdidparam = NULL;
	END IF;
	SELECT 	f.factoryid
			, fd.fdid
			, f.factoryname
            , s.skuid
            , s.skucode
            , s.sku_description
            , d.depotid
            , d.depotname
            , date_required
            , fd.netWeight
            , fd.grossWeight
            , fd.customerno
	FROM 	factory_delivery fd
    INNER JOIN factory f ON f.factoryid = fd.factoryid
    INNER JOIN sku s ON s.skuid = fd.skuid
    INNER JOIN depot d ON d.depotid = fd.depotid
	WHERE 	(fd.customerno = custno OR custno IS NULL)
    AND 	(fdid = fdidparam OR fdidparam IS NULL)
	AND 	fd.isdeleted = 0;
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `get_vehtypetransporter_mapping`$$
CREATE PROCEDURE `get_vehtypetransporter_mapping`(
	IN custno INT
	,IN transid INT
)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
	IF(transid = '' OR transid = 0) THEN
		SET transid = NULL;
	END IF;
       
	SELECT 	vtm.vehicletypeid
            ,vtm.vehiclecount
            ,vt.vehiclecode
			,vt.vehicledescription
            ,vt.volume
            ,vt.weight
			,vtm.transporterid
	FROM 	vehtypetransmapping vtm
	INNER JOIN vehicletype vt ON vtm.vehicletypeid = vt.vehicletypeid
	WHERE 	(vtm.customerno = custno OR custno IS NULL)
	AND 	(vtm.transporterid = transid OR transid IS NULL) 
	AND 	vtm.isdeleted=0 
	AND 	vt.isdeleted=0
	ORDER BY vt.volume DESC;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_transporteractualshare`$$
CREATE PROCEDURE `get_transporteractualshare`(
	IN custno INT
    , IN currenttransporterid INT
    , IN currentfactid INT
    , IN currentzoneid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(currenttransporterid = '' OR currenttransporterid = 0) THEN
		SET currenttransporterid = NULL;
	END IF;
	IF(currentfactid = '' OR currentfactid = 0) THEN
		SET currentfactid = NULL;
	END IF;
    IF(currentzoneid = '' OR currentzoneid = 0) THEN
		SET currentzoneid = NULL;
	END IF;
    
	SELECT    tas.transporterid
			, tas.zoneid
			, tas.factoryid			
			, tas.total_weight
			, tas.shared_weight
			, tas.actualpercent
			, tas.customerno
   FROM 	transporter_actualshare AS tas
   WHERE 	(tas.customerno = custno OR custno IS NULL)
   AND		(tas.transporterid = currenttransporterid OR currenttransporterid IS NULL)
   AND		(tas.zoneid = currentzoneid OR currentzoneid IS NULL)
   AND		(tas.factoryid = currentfactid OR currentfactid IS NULL)
   AND 		tas.isdeleted = 0;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `update_transporteractualshare`$$
CREATE PROCEDURE `update_transporteractualshare`( 
	IN transid INT
	, IN factid INT
	, IN zid INT
	, IN sharedwt decimal(11,3)
	, IN totalwt decimal(11,3)
	, IN custno INT
    , IN todaysdate DATETIME
    , IN userid INT
    
	)
BEGIN
    DECLARE actualsharepercent DECIMAL(5,2);
    DECLARE tempsharedwt decimal(11,3);
    DECLARE temptotalwt decimal(11,3);
    
    SELECT shared_weight, total_weight
	INTO 	tempsharedwt, temptotalwt
    FROM 	transporter_actualshare
    WHERE 	transporterid = transid
    AND		factoryid = factid
    AND 	zoneid = zid
    AND 	customerno = custno;
	
	SET 	tempsharedwt = tempsharedwt + sharedwt;
    SET		temptotalwt = temptotalwt + totalwt;
    
    SET		actualsharepercent = (tempsharedwt/temptotalwt) * 100;
    
	UPDATE `transporter_actualshare`
	SET 	`shared_weight` = tempsharedwt
			,`total_weight` = temptotalwt
            , `actualpercent` = actualsharepercent
			, `updated_on` = todaysdate
			, `updated_by`= userid
	WHERE 	transporterid = transid
    AND		factoryid = factid
    AND 	zoneid = zid
    AND 	customerno = custno;

END$$
DELIMITER ;



/* Proposed Indent Changes */

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_transporter_proposed_indent`$$
CREATE PROCEDURE `get_transporter_proposed_indent`(
	IN custno INT
 , IN propindentid INT
    , IN transid INT
    
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
	AND 	pit.isdeleted = 0;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `update_pit_mapping`$$
CREATE  PROCEDURE `update_pit_mapping`( 
	IN pitmapid INT
    , IN actual_vehtypeid INT
    , IN vehicleno varchar(20)
    , IN drivermobileno varchar(12)
    , IN isAccepted tinyint(1)
	, IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE 	proposed_indent_transporter_mapping
	SET 	actual_vehicletypeid = actual_vehtypeid
            , vehicleno = vehicleno
			, drivermobileno = drivermobileno
            , isAccepted = isAccepted
			, updated_on = todaysdate 
            , updated_by = userid
	WHERE 	pitmappingid = pitmapid;

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `update_proposed_indent`$$
CREATE PROCEDURE `update_proposed_indent`( 
	IN propindentid int
	, IN factoryid int
    , IN depotid int
	, IN total_weight decimal(7,3)
	, IN total_volume decimal(7,3)
	, IN isApprovedParam TINYINT(1)
	, IN hasTransAccepted TINYINT(1)
	, IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	IF	((propindentid IS NOT NULL && propindentid != '' && propindentid != '0')
		AND (factoryid IS NOT NULL && factoryid !='' && factoryid !='0')
		AND (depotid IS NOT NULL && depotid !='' && depotid !='0')
        AND (total_weight IS NOT NULL && total_weight !='')
        AND (total_volume IS NOT NULL && total_volume != '')) THEN
			UPDATE 	proposed_indent
			SET 	factoryid = factoryid
					, depotid = depotid
					, total_weight = total_weight
					, total_volume = total_volume
					, updated_on = todaysdate 
					, updated_by = userid
			WHERE	proposedindentid = propindentid;
    ELSEIF (hasTransAccepted IS NOT NULL && hasTransAccepted != '') THEN 
			UPDATE 	proposed_indent
			SET 	hasTransporterAccepted = hasTransAccepted
					, updated_on = todaysdate 
					, updated_by = userid
			WHERE proposedindentid = propindentid;	
	ELSEIF (isApprovedParam IS NOT NULL && isApprovedParam != '') THEN
			UPDATE 	proposed_indent
			SET 	 isApproved = isApprovedParam
					, updated_on = todaysdate 
					, updated_by = userid
			WHERE	proposedindentid = propindentid;
	END IF ;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `assign_proposed_indent_to_next_transporter`$$
CREATE  PROCEDURE `assign_proposed_indent_to_next_transporter`( 
	IN proposedindentid INT
    , IN proposed_transporterid INT
    , IN proposed_vehicletypeid INT
    , IN factid INT
    , IN depotidparam INT
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
    WHERE 		depotid = depotidparam
	AND			d.customerno = custno
	AND			d.isdeleted = 0;
    
    IF(zid IS NOT NULL && zid != 0) THEN
		BEGIN
			SELECT 	sharepercent
			INTO 	temp_sharepercent
			FROM 	transportershare
			WHERE 	transporterid = proposed_transporterid
			AND		factoryid = factid
			AND 	zoneid = zid
            AND		customerno = custno
			AND		isdeleted = 0;
			
			IF(temp_sharepercent IS NOT NULL && temp_sharepercent != 0) THEN
				BEGIN
					SELECT 		t.transporterid
					INTO 		next_proposed_transporter_id
					FROM 		transportershare AS ts
					INNER JOIN 	transporter AS t ON t.transporterid = ts.transporterid
					INNER JOIN	vehtypetransmapping vtm ON vtm.transporterid = t.transporterid
					INNER JOIN 	vehicletype AS vt ON vt.vehicletypeid = vtm.vehicletypeid
					WHERE 		ts.sharepercent < temp_sharepercent
					AND			ts.zoneid = zid
					AND			ts.factoryid = factid
                    			AND			vtm.vehicletypeid = proposed_vehicletypeid
					AND			ts.customerno = custno
					AND 		ts.isdeleted = 0
                    AND 		t.isdeleted = 0
                    AND 		vt.isdeleted = 0
					ORDER BY 	sharepercent DESC 
                    LIMIT 1;
					
                    IF (next_proposed_transporter_id IS NOT NULL && next_proposed_transporter_id != 0) THEN
						BEGIN
							START TRANSACTION;
							CALL `insert_pit_mapping`(proposedindentid
						    , next_proposed_transporter_id
                                                    , proposed_vehicletypeid
                                                    , custno
                                                    , todaysdate
                                                    , userid
                                                    , @currentpitmappingid);
							SELECT 	@currentpitmappingid 
                            INTO	currentpitmappingid
                            FROM 	DUAL;
                            
                            IF(currentpitmappingid IS NOT NULL && currentpitmappingid != 0) THEN
								CALL `update_proposed_indent`(proposedindentid, NULL, NULL, NULL, NULL, NULL, 0, todaysdate, userid);
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





