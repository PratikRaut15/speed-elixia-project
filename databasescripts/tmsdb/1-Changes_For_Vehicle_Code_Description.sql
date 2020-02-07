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
            , vehtype.vehicledescription as proposedvehicledescription
			, vehtype.vehiclecode as proposedvehiclecode
            , veh.vehicledescription as actualvehicledescription
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
	AND	(pit.proposedindentid = propindentid OR propindentid IS NULL)
    	AND	(pit.proposed_transporterid = transid OR transid IS NULL)
    	AND	(pit.pitmappingid = pitidparam OR pitidparam IS NULL)
	AND 	pit.isdeleted = 0;
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
DROP PROCEDURE IF EXISTS get_indent$$
CREATE PROCEDURE get_indent(
	IN custno INT
    	, IN indentidparam INT
    	, IN factoryidparam INT
)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    	IF(indentidparam = '' OR indentidparam = 0) THEN
		SET indentidparam = NULL;
	END IF;
    	IF(factoryidparam = '' OR factoryidparam = 0) THEN
		SET factoryidparam = NULL;
	END IF;
    
	SELECT 
	    i.indentid,
	    t.transporterid,
	    t.transportername,
	    i.vehicleno,
	    i.proposedindentid,
	    i.proposed_vehicletypeid,
	    i.actual_vehicletypeid,
	    i.factoryid,
	    f.factoryname,
	    i.depotid,
	    d.depotname,
	    vehtype.vehiclecode AS proposedvehiclecode,
	    vehtype.vehicledescription as proposedvehicledescription,
	    veh.vehiclecode AS actualvehiclecode,
	    veh.vehicledescription as actualvehicledescription,
	    i.date_required,
	    i.loadstatus,
	    shipmentno,
	    i.remarks,
	    totalweight,
	    totalvolume,
	    isdelivered,
	    ism.skuid,
	    ism.no_of_units,
	    s.sku_description,
	    i.customerno,
	    i.created_on,
	    i.created_by,
	    i.updated_on,
	    i.updated_by
	FROM
	    indent i
		INNER JOIN
	    transporter t ON t.transporterid = i.transporterid
		INNER JOIN
	    factory f ON f.factoryid = i.factoryid
		INNER JOIN
	    depot d ON d.depotid = i.depotid
		INNER JOIN
	    vehicletype vehtype ON vehtype.vehicletypeid = i.proposed_vehicletypeid
		LEFT JOIN
	    vehicletype veh ON veh.vehicletypeid = i.actual_vehicletypeid
		LEFT JOIN
	    indent_sku_mapping ism ON ism.indentid = i.indentid
		LEFT JOIN
	    sku s ON s.skuid = ism.skuid
	WHERE
	    (i.customerno = custno OR custno IS NULL)
		AND (i.indentid = indentidparam
		OR indentidparam IS NULL)
		AND (i.factoryid = factoryidparam
		OR factoryidparam IS NULL)
		AND i.isdeleted = 0;
END$$
DELIMITER ;







DELIMITER $$
DROP PROCEDURE IF EXISTS get_assigned_transporter$$ 
CREATE PROCEDURE get_assigned_transporter(
	IN custno INT,
    	IN proposedindentidparam INT
)
BEGIN
	
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    	END IF;
    	IF(proposedindentidparam = '' OR proposedindentidparam = 0) THEN
		SET proposedindentidparam = NULL;
    	END IF;
    
    	SELECT 
		pi.proposedindentid
		, pi.factoryid
		, f.factoryname
		, pi.depotid
		, d.depotname
		, pi.date_required
		, pit.proposed_transporterid
		, pit.proposed_vehicletypeid
		, v.vehiclecode as proposedvehiclecode
		, v.vehicledescription as proposedvehicledescription
		, veh.vehiclecode as actualvehiclecode
		, veh.vehicledescription as actualvehicledescription
		, t.transportername
		, u.email
		, u.phone
	FROM proposed_indent as pi
	    INNER JOIN proposed_indent_transporter_mapping as pit on pit.proposedindentid = pi.proposedindentid and pit.isAccepted = 0
	    INNER JOIN factory as f on f.factoryid = pi.factoryid
	    INNER JOIN depot as d on d.depotid = pi.depotid
	    INNER JOIN vehicletype as v on v.vehicletypeid = pit.proposed_vehicletypeid
	    LEFT JOIN vehicletype as veh on veh.vehicletypeid = pit.actual_vehicletypeid
	    INNER JOIN transporter as t on t.transporterid = pit.proposed_transporterid
	    INNER JOIN tmsmapping as tm on tm.tmsid = t.transporterid and tm.role = 'transporter'
	    INNER JOIN speed.user as u on u.userid = tm.userid 
	WHERE (pi.customerno = custno OR custno IS NULL)
	    AND (pi.proposedindentid = proposedindentidparam OR proposedindentidparam IS NULL)
	    AND pi.hasTransporterAccepted = 0
	    AND pi.isdeleted=0;
    
    
END$$
DELIMITER ;




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
	FROM proposed_indent as pi
	    INNER JOIN proposed_indent_transporter_mapping as pit on pit.proposedindentid = pi.proposedindentid and pit.isAccepted = 0
	    INNER JOIN factory as f on f.factoryid = pi.factoryid
	    INNER JOIN depot as d on d.depotid = pi.depotid
	    INNER JOIN vehicletype as v on v.vehicletypeid = pit.proposed_vehicletypeid
	    LEFT JOIN vehicletype as veh on veh.vehicletypeid = pit.actual_vehicletypeid
	WHERE (pi.customerno = custno OR custno IS NULL)
	    AND pi.hasTransporterAccepted = 0
	    AND (pi.date_required + INTERVAL 1 DAY + INTERVAL -1 SECOND) <= dateparam
	    AND pi.isdeleted=0;   
END$$
DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS get_autoreject_indent$$ 
CREATE PROCEDURE get_autoreject_indent(
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
	FROM proposed_indent as pi
	    INNER JOIN proposed_indent_transporter_mapping as pit on pit.proposedindentid = pi.proposedindentid and pit.isAccepted = 0
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

CREATE TABLE IF NOT EXISTS `dbpatches` (
  `patchid` int(11) NOT NULL,
  `patchdate` datetime NOT NULL,
  `appliedby` varchar(20) NOT NULL,
  `patchdesc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `dbpatches`
 ADD PRIMARY KEY (`patchid`);


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 1, NOW(), 'Shrikant Suryawanshi','Changes For Vehicle Code And Description');



