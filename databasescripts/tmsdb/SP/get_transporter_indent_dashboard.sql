DELIMITER $$
DROP PROCEDURE IF EXISTS `get_transporter_indent_dashboard`$$
CREATE PROCEDURE `get_transporter_indent_dashboard`(
	IN custno INT
    , IN transporteridparam INT
    , IN startdate DATE
    , IN enddate DATE		
)
BEGIN
	 
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    IF(transporteridparam = '' OR transporteridparam = 0) THEN
		SET transporteridparam = NULL;
    END IF;
    IF(startdate = '') THEN
		SET startdate = NULL;
    END IF;
    IF(enddate = '') THEN
		SET enddate = NULL;
    END IF;
    
    SELECT 
		 count(distinct(pi.proposedindentid)) as totalindent
		, pit.proposed_transporterid
        , t.transportername
        , count(awaiting.pitmappingid) as awaitingindent
        , count(expired.pitmappingid) as expiredindent
        , count(rejected.pitmappingid) as rejectedindent
        , count(autorejected.pitmappingid) as autorejectedindent
        , count(accepted.pitmappingid) as acceptedindent
        
    FROM proposed_indent as pi
    LEFT OUTER JOIN proposed_indent_transporter_mapping as pit on pi.proposedindentid = pit.proposedindentid
    INNER JOIN transporter as t on t.transporterid = pit.proposed_transporterid
    INNER JOIN depot as d on d.depotid = pi.depotid
    INNER JOIN zone as z on z.zoneid = d.zoneid
    INNER JOIN vehicletype as veh on pit.proposed_vehicletypeid = veh.vehicletypeid
    LEFT OUTER JOIN proposed_indent_transporter_mapping as awaiting on awaiting.pitmappingid = pit.pitmappingid and awaiting.isAccepted = 0 AND awaiting.isAutoRejected = 0
    LEFT OUTER JOIN proposed_indent_transporter_mapping as expired on expired.pitmappingid = pit.pitmappingid and expired.isAccepted = 0 AND expired.isAutoRejected = 1
    LEFT OUTER JOIN proposed_indent_transporter_mapping as rejected on rejected.pitmappingid = pit.pitmappingid and rejected.isAccepted = -1 AND rejected.isAutoRejected = 0
    LEFT OUTER JOIN proposed_indent_transporter_mapping as autorejected on autorejected.pitmappingid = pit.pitmappingid and autorejected.isAccepted = -1 AND autorejected.isAutoRejected = 1
    LEFT OUTER JOIN proposed_indent_transporter_mapping as accepted on accepted.pitmappingid = pit.pitmappingid and accepted.isAccepted = 1
    
    WHERE (pi.customerno = custno OR custno IS NULL)
    AND (pit.proposed_transporterid = transporteridparam OR transporteridparam IS NULL)
    AND (pi.date_required BETWEEN startdate AND enddate OR (startdate IS NULL AND enddate IS NULL))
    AND pi.isdeleted = 0
    group by pit.proposed_transporterid;
		
END$$
DELIMITER ;
