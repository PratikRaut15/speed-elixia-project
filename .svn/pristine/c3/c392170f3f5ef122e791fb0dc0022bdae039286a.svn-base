DELIMITER $$
DROP PROCEDURE IF EXISTS `get_transporter_indent_count`$$
CREATE  PROCEDURE `get_transporter_indent_count`(
	IN custno INT,
	IN transporteridparam INT
)
BEGIN
	
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
	IF(transporteridparam = '' OR transporteridparam = 0) THEN
		SET transporteridparam = NULL;
	END IF;
    
    	SELECT 
		count(pi.proposedindentid) as indentcount
		, pi.factoryid
		, f.factoryname
		, pi.date_required
		, pit.isAccepted
	FROM proposed_indent_transporter_mapping as pit
    INNER JOIN proposed_indent as pi on pi.proposedindentid = pit.proposedindentid
    INNER JOIN transporter as t on t.transporterid = pit.proposed_transporterid
	INNER JOIN factory as f on f.factoryid = pi.factoryid
    INNER JOIN depot as d on d.depotid = pi.depotid
	INNER JOIN vehicletype as v on v.vehicletypeid = pit.proposed_vehicletypeid
	LEFT JOIN vehicletype as veh on veh.vehicletypeid = pit.actual_vehicletypeid
	WHERE (pit.customerno = custno OR custno IS NULL)
    AND (pit.proposed_transporterid = transporteridparam OR transporteridparam IS NULL)
	    AND pit.isAccepted = 0
	    AND pi.isdeleted=0
        AND pit.isdeleted=0
	group by pi.factoryid; 
END$$
DELIMITER ;
