DELIMITER $$
DROP PROCEDURE IF EXISTS `get_transporter_proposed_indent`$$
CREATE  PROCEDURE `get_transporter_proposed_indent`(
	IN custno INT
	, IN propindentid INT
    	, IN transid INT
    	, IN pitidparam INT
        , IN startdate DATE
	, IN enddate DATE
    
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
    IF(startdate = '' OR startdate = 0) THEN
		SET startdate = NULL;
    END IF;
    IF(enddate = '' OR enddate = 0) THEN
		SET enddate = NULL;
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
            , pit.isAutoRejected
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
        AND (pi.date_required BETWEEN startdate AND enddate 
        OR (startdate IS NULL AND enddate IS NULL))
	AND 	pit.isdeleted = 0;
END$$
DELIMITER ;
