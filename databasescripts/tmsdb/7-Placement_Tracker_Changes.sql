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
            pit.remarks as transporterremarks,
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
 VALUES ( 7, NOW(), 'Shrikant Suryawanshi','Placement Tracker Changes');
