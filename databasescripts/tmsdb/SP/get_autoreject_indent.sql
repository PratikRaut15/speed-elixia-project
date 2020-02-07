DELIMITER $$
DROP PROCEDURE IF EXISTS `get_autoreject_indent`$$
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
