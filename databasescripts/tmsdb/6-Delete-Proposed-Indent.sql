Create index index_skumapping_proposedindentid on proposed_indent_sku_mapping (proposedindentid);


DELIMITER $$
DROP PROCEDURE IF EXISTS delete_proposed_indent$$
CREATE PROCEDURE `delete_proposed_indent`( 
	IN propindentid int
    , IN remarkParam varchar(250)
    , IN todaysdate DATETIME
    , IN userid INT
    
)
BEGIN
DECLARE indentid INT;
DECLARE EXIT HANDLER FOR SQLEXCEPTION
     BEGIN
       -- ERROR
     ROLLBACK;
	 END;
   
    START TRANSACTION;
    BEGIN
    
    SELECT proposedindentid
    INTO indentid
    FROM indent
    WHERE proposedindentid = propindentid
    AND isdeleted = 0;
    
    IF(indentid IS NULL) THEN
	UPDATE 	proposed_indent
	SET 	isdeleted = 1
			,remark =  COALESCE(remarkParam, remark)
			,updated_on = todaysdate
            ,updated_by = userid
	WHERE 	proposedindentid = propindentid;
    
	CALL delete_pit_mapping(NULL,propindentid,todaysdate,userid);
    CALL delete_sku_mapping(NULL,propindentid,todaysdate,userid);
    
	END IF;
    
	COMMIT;
    END;
	
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS delete_pit_mapping$$
CREATE PROCEDURE `delete_pit_mapping`( 
	IN pitmapid int
    , IN propindentid INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	IF (pitmapid = '' OR pitmapid = 0) THEN
		SET pitmapid = NULL;
    END IF;
    IF (propindentid = '' OR propindentid = 0) THEN
		SET propindentid = NULL;
    END IF;
	UPDATE  proposed_indent_transporter_mapping
	SET 	isdeleted = 1
			, updated_on = todaysdate
            , updated_by = userid
	WHERE 	(pitmappingid = pitmapid	OR pitmapid IS NULL)
    AND 	(proposedindentid = propindentid OR propindentid IS NULL);

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS delete_sku_mapping$$
CREATE PROCEDURE `delete_sku_mapping`( 
	IN skumapid int
    , IN propindentid INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	IF (skumapid = '' OR skumapid = 0) THEN
		SET skumapid = NULL;
    END IF;
    IF (propindentid = '' OR propindentid = 0) THEN
		SET propindentid = NULL;
    END IF;
    
    
	UPDATE  proposed_indent_sku_mapping
	SET 	isdeleted = 1
			, updated_on = todaysdate
            , updated_by = userid
	WHERE 	(proposed_indent_sku_mappingid = skumapid	OR skumapid IS NULL)
    AND 	(proposedindentid = propindentid OR propindentid IS NULL);

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS get_proposed_indent$$
CREATE PROCEDURE get_proposed_indent(
	IN custno INT
    	, IN propindentid INT
		, IN factoryidparam INT
        , IN transporteridparam INT
    	, IN daterequired varchar(15)
    	, IN isAcceptedparam INT 
)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
        IF(propindentid = '' OR propindentid = 0) THEN
		SET propindentid = NULL;
	END IF;
        IF(factoryidparam = '' OR factoryidparam = 0) THEN
		SET factoryidparam = NULL;
	END IF;
    IF(transporteridparam = '' OR transporteridparam = 0) THEN
		SET transporteridparam = NULL;
	END IF;
        IF(daterequired = '' OR daterequired = 0) THEN
		SET daterequired = NULL;
	END IF;
    	IF(isAcceptedparam = '' OR isAcceptedparam = 0) THEN
		SET isAcceptedparam = NULL;
	END IF;
	SELECT 
	    pi.proposedindentid,
	    pi.factoryid,
	    pi.hasTransporterAccepted,
	    pi.isApproved,
	    f.factoryname,
	    pi.depotid,
	    d.depotname,
	    t.transporterid,
	    t.transportername,
	    vehtype.vehiclecode,
	    vehtype.vehicledescription as proposedvehicledescription,
	    veh.vehiclecode AS actualvehiclecode,
	    veh.vehicledescription as actualvehicledescription,
	    pit.vehicleno,
	    pi.date_required,
	    pit.proposed_vehicletypeid,
	    pit.actual_vehicletypeid,
	    pit.isAccepted,
        pit.isAutoRejected,
	    pit.vehicleno,
	    pit.drivermobileno,
	    pi.remark as piremark,
	    total_weight,
	    total_volume,
	    pi.customerno,
	    pi.created_on,
	    pi.updated_on,
	    pi.created_by,
	    pi.updated_by,
	    i.loadstatus,
	    i.shipmentno,
	    i.remarks
	FROM
	    proposed_indent pi
		INNER JOIN
	    proposed_indent_transporter_mapping pit ON pit.proposedindentid = pi.proposedindentid
		INNER JOIN
	    factory f ON f.factoryid = pi.factoryid
		INNER JOIN
	    depot d ON d.depotid = pi.depotid
		INNER JOIN
	    transporter t ON t.transporterid = pit.proposed_transporterid
		INNER JOIN
	    vehicletype vehtype ON vehtype.vehicletypeid = pit.proposed_vehicletypeid
		LEFT JOIN
	    vehicletype veh ON veh.vehicletypeid = pit.actual_vehicletypeid
		LEFT JOIN
	    indent i ON i.proposedindentid = pi.proposedindentid
	WHERE
	    (pi.customerno = custno OR custno IS NULL)
		AND (pi.proposedindentid = propindentid
		OR propindentid IS NULL)
		AND (pi.factoryid = factoryidparam
		OR factoryidparam IS NULL)
        AND (pit.proposed_transporterid = transporteridparam
		OR transporteridparam IS NULL)
		AND (pi.date_required = daterequired
		OR daterequired IS NULL)
		AND (pit.isAccepted = isAcceptedparam
		OR isAcceptedparam IS NULL)
		AND pi.isdeleted = 0;
END$$
DELIMITER ;


/* Changes For Zonename -- */

DELIMITER $$
DROP PROCEDURE IF EXISTS get_proposed_indent$$
CREATE PROCEDURE get_proposed_indent(
	IN custno INT
    	, IN propindentid INT
		, IN factoryidparam INT
        , IN transporteridparam INT
    	, IN daterequired varchar(15)
    	, IN isAcceptedparam INT 
)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
        IF(propindentid = '' OR propindentid = 0) THEN
		SET propindentid = NULL;
	END IF;
        IF(factoryidparam = '' OR factoryidparam = 0) THEN
		SET factoryidparam = NULL;
	END IF;
    IF(transporteridparam = '' OR transporteridparam = 0) THEN
		SET transporteridparam = NULL;
	END IF;
        IF(daterequired = '' OR daterequired = 0) THEN
		SET daterequired = NULL;
	END IF;
    	IF(isAcceptedparam = '' OR isAcceptedparam = 0) THEN
		SET isAcceptedparam = NULL;
	END IF;
	SELECT 
	    pi.proposedindentid,
	    pi.factoryid,
	    pi.hasTransporterAccepted,
	    pi.isApproved,
	    f.factoryname,
	    pi.depotid,
	    d.depotname,
	    t.transporterid,
	    t.transportername,
	    vehtype.vehiclecode,
	    vehtype.vehicledescription as proposedvehicledescription,
	    veh.vehiclecode AS actualvehiclecode,
	    veh.vehicledescription as actualvehicledescription,
	    pit.vehicleno,
	    pi.date_required,
	    pit.proposed_vehicletypeid,
	    pit.actual_vehicletypeid,
	    pit.isAccepted,
        pit.isAutoRejected,
	    pit.vehicleno,
	    pit.drivermobileno,
	    pi.remark as piremark,
	    total_weight,
	    total_volume,
	    pi.customerno,
	    pi.created_on,
	    pi.updated_on,
	    pi.created_by,
	    pi.updated_by,
	    i.loadstatus,
	    i.shipmentno,
	    i.remarks,
        z.zonename
	FROM
	    proposed_indent pi
		INNER JOIN
	    proposed_indent_transporter_mapping pit ON pit.proposedindentid = pi.proposedindentid
		INNER JOIN
	    factory f ON f.factoryid = pi.factoryid
		INNER JOIN
	    depot d ON d.depotid = pi.depotid
        INNER JOIN 
        zone z ON d.zoneid = z.zoneid
		INNER JOIN
	    transporter t ON t.transporterid = pit.proposed_transporterid
		INNER JOIN
	    vehicletype vehtype ON vehtype.vehicletypeid = pit.proposed_vehicletypeid
		LEFT JOIN
	    vehicletype veh ON veh.vehicletypeid = pit.actual_vehicletypeid
		LEFT JOIN
	    indent i ON i.proposedindentid = pi.proposedindentid
	WHERE
	    (pi.customerno = custno OR custno IS NULL)
		AND (pi.proposedindentid = propindentid
		OR propindentid IS NULL)
		AND (pi.factoryid = factoryidparam
		OR factoryidparam IS NULL)
        AND (pit.proposed_transporterid = transporteridparam
		OR transporteridparam IS NULL)
		AND (pi.date_required = daterequired
		OR daterequired IS NULL)
		AND (pit.isAccepted = isAcceptedparam
		OR isAcceptedparam IS NULL)
		AND pi.isdeleted = 0;
END$$
DELIMITER ;



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 6, NOW(), 'Shrikant Suryawanshi','Delete Proposed Indent');
