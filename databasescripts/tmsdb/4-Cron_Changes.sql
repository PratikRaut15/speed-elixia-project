update indent SET loadstatus=-1 WHERE loadstatus=2;
ALTER TABLE proposed_indent_transporter_mapping ADD isAutoRejected tinyint(1) DEFAULT 0 AFTER updated_by;


DELIMITER $$
DROP PROCEDURE IF EXISTS get_pending_indent$$ 
CREATE PROCEDURE get_pending_indent(
	IN custno INT,
    	IN dateparam datetime 
)
BEGIN
	
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    	END IF;
    	IF(dateparam = '' OR dateparam = '0000-00-00 00:00:00') THEN
		SET dateparam = NULL;
    	END IF;
    
    	SELECT 
		pi.proposedindentid
		, pi.factoryid
		, f.factoryname
		, pi.depotid
		, d.depotname
		, pi.date_required
		, pit.proposed_vehicletypeid
		, v.vehiclecode as proposedvehiclecode
		, v.vehicledescription as proposedvehicledescription
		, veh.vehiclecode as actualvehiclecode
		, veh.vehicledescription as actualvehicledescription
        , pit.vehicleno
        , pit.actual_vehicletypeid
        , pit.proposed_transporterid
        , pit.drivermobileno
        , pit.pitmappingid
        , pit.isAccepted
        
	FROM proposed_indent as pi
	    INNER JOIN proposed_indent_transporter_mapping as pit on pit.proposedindentid = pi.proposedindentid and pit.isAccepted = 0 AND pit.isAutoRejected = 0 
	    INNER JOIN factory as f on f.factoryid = pi.factoryid
	    INNER JOIN depot as d on d.depotid = pi.depotid
	    INNER JOIN vehicletype as v on v.vehicletypeid = pit.proposed_vehicletypeid
	    LEFT JOIN vehicletype as veh on veh.vehicletypeid = pit.actual_vehicletypeid
	WHERE (pi.customerno = custno OR custno IS NULL)
	    AND pi.hasTransporterAccepted = 0
	    AND (pi.date_required + INTERVAL 1 DAY ) <= dateparam
        AND pi.isdeleted=0;   
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS get_autoreject_indent$$
CREATE PROCEDURE `get_autoreject_indent`(
	IN custno INT,
    IN dateparam datetime 
)
BEGIN
	
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    IF(dateparam = '' OR dateparam = '0000-00-00 00:00:00') THEN
		SET dateparam = NULL;
    END IF;
    
    SELECT 
		pi.proposedindentid
        , pi.factoryid
		, pi.depotid
        , pit.proposed_transporterid
        , pit.proposed_vehicletypeid
        , pit.vehicleno
        , pit.pitmappingid
        , pit.drivermobileno
        , pit.isAccepted
        , pit.remarks
        , pit.actual_vehicletypeid
        , pi.date_required
		, f.factoryname
		, d.depotname
        , v.vehiclecode as proposedvehiclecode
        , v.vehicledescription as proposedvehicledescription
        , veh.vehiclecode as actualvehiclecode
        , veh.vehicledescription as actualvehicledescription
        , pit.isAutoRejected
        , pit.created_on
	FROM proposed_indent as pi
    INNER JOIN proposed_indent_transporter_mapping as pit on pit.proposedindentid = pi.proposedindentid and pit.isAccepted = 0 AND pit.isAutoRejected=0
    INNER JOIN depot as d on d.depotid = pi.depotid
    INNER JOIN factory as f on f.factoryid = pi.factoryid
    INNER JOIN vehicletype as v on v.vehicletypeid = pit.proposed_vehicletypeid	 
    LEFT JOIN vehicletype as veh on veh.vehicletypeid = pit.actual_vehicletypeid
    WHERE (pi.customerno = custno OR custno IS NULL)
    AND pi.hasTransporterAccepted = 0
	AND pit.created_on < dateparam
    AND pi.isdeleted=0;
    
    
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
    , IN remarksParam varchar(250)
    , IN isAutoRejectedParam tinyint(1)
	, IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	IF(isAutoRejectedParam = '' OR isAutoRejectedParam = 0) THEN
		SET isAutoRejectedParam = 0;
    END IF;
	UPDATE 	proposed_indent_transporter_mapping
	SET 	actual_vehicletypeid = actual_vehtypeid
            , vehicleno = vehicleno
			, drivermobileno = drivermobileno
            , isAccepted = isAccepted
            , remarks =  COALESCE(remarksParam, remarks)
			, updated_on = todaysdate 
            , updated_by = userid
            , isAutoRejected = isAutoRejectedParam
	WHERE 	pitmappingid = pitmapid;

END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS get_proposed_indent$$
CREATE PROCEDURE get_proposed_indent(
	IN custno INT
    	, IN propindentid INT
	, IN factoryidparam INT
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
		AND (pi.date_required = daterequired
		OR daterequired IS NULL)
		AND (pit.isAccepted = isAcceptedparam
		OR isAcceptedparam IS NULL)
		AND pi.isdeleted = 0;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS get_skuweight_byfactorydepot$$
CREATE PROCEDURE get_skuweight_byfactorydepot(
   IN custno INT
   , IN datereq datetime
   , IN factoryidparam INT
)
BEGIN
   IF(custno = '' OR custno = 0) THEN
       SET custno = NULL;
   END IF;
   IF(datereq = '' OR datereq LIKE '%0000-00-00%') THEN
       SET datereq = NULL;
   END IF;
   IF(factoryidparam = '' OR factoryidparam = 0) THEN
       SET factoryidparam = NULL;
   END IF;
   IF (custno IS NOT NULL AND datereq IS NOT NULL) THEN
        BEGIN
            SELECT     sum(fd.grossWeight) as weight
                    , fd.date_required
                    , fd.factoryid
                    , fd.depotid
            FROM     factory_delivery fd
            WHERE   customerno = custno
            AND     fd.date_required = datereq
            AND     (fd.factoryid = factoryidparam OR factoryidparam IS NULL)
            AND     fd.isProcessed = 0
            AND     fd.depotid <> 0
            AND       fd.factoryid <> 0
            AND       fd.isdeleted = 0
            GROUP BY date_required, factoryid, depotid;
        END;
    END IF;
END$$
DELIMITER ;



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 4, NOW(), 'Shrikant Suryawanshi','Cron Changes');
