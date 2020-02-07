DELIMITER $$
DROP PROCEDURE IF EXISTS `get_assigned_transporter`$$
CREATE PROCEDURE `get_assigned_transporter`(
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
        , v.vehiclecode
        , t.transportername
        , u.email
        , u.phone
	FROM proposed_indent as pi
    INNER JOIN proposed_indent_transporter_mapping as pit on pit.proposedindentid = pi.proposedindentid and pit.isAccepted = 0
    INNER JOIN factory as f on f.factoryid = pi.factoryid
    INNER JOIN depot as d on d.depotid = pi.depotid
    INNER JOIN vehicletype as v on v.vehicletypeid = pit.proposed_vehicletypeid
    INNER JOIN transporter as t on t.transporterid = pit.proposed_transporterid
    INNER JOIN tmsmapping as tm on tm.tmsid = t.transporterid and tm.role = 'transporter'
    INNER JOIN speed.user as u on u.userid = tm.userid 
	WHERE (pi.customerno = custno OR custno IS NULL)
    AND (pi.proposedindentid = proposedindentidparam OR proposedindentidparam IS NULL)
    AND pi.hasTransporterAccepted = 0
    AND pi.isdeleted=0
    AND u.isdeleted = 0
    AND tm.isdeleted = 0;
		
    
    
END$$
DELIMITER '
