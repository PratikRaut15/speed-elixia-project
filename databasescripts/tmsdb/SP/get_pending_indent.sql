DELIMITER $$
DROP PROCEDURE IF EXISTS `get_pending_indent`$$
CREATE PROCEDURE `get_pending_indent`(
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

