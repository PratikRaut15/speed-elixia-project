/*
	Name			- get_transporter_efficiency
    Description 	-	To get transporter efficiency
    Parameters		-	customernoparam, factoryid, depotid, transporteid, zoneid, typeid, startdate, enddate
    Module			-TMS
    Sub-Modules 	- 	Placement Efficiency
    Sample Call		-	CALL get_transporter_efficiency('116','','','','5','2015-11-10','2015-12-15');

    Created by		-	Shrikant 
    Created	on		- Nov, 2015
    Change details 	-
    1) 	Updated by	-	Shrikant Suryawanshi
	Updated	on	-	15 Dec 2015 
        Reason		-	Add startdate, enddate, zoneid, typeid for filtering data
    2)  Updated by	-	Shrikant Suryawanshi
	Updated	on	-	21 Dec 2015 
        Reason		-	to check isdeleted records
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS get_transporter_efficiency$$ 
CREATE PROCEDURE get_transporter_efficiency(
	IN custno INT
    , IN factoryidparam INT
    , IN depotidparam INT
    , IN transporteridparam INT
    , IN zoneidparam INT
	, IN typeidparam INT
    , IN startdate DATE
    , IN enddate DATE		
)
BEGIN
	 
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    IF(factoryidparam = '' OR factoryidparam = 0) THEN
		SET factoryidparam = NULL;
    END IF;
    IF(depotidparam = '' OR depotidparam = 0) THEN
		SET depotidparam = NULL;
    END IF;
    IF(transporteridparam = '' OR transporteridparam = 0) THEN
		SET transporteridparam = NULL;
    END IF;
    IF(zoneidparam = '' OR zoneidparam = 0) THEN
		SET zoneidparam = NULL;
    END IF;
    IF(typeidparam = '' OR typeidparam = 0) THEN
		SET typeidparam = NULL;
    END IF;
    IF(startdate = '' OR startdate = 0) THEN
		SET startdate = NULL;
    END IF;
    IF(enddate = '' OR enddate = 0) THEN
		SET enddate = NULL;
    END IF;
    
    SELECT 
		 count(distinct(pi.proposedindentid)) as totalindent
		, pit.proposed_transporterid
        , t.transportername
        , pit.isAccepted
        , count(pitplaced.isAccepted) as placed
        , count(pitrejected.isAccepted) as rejected
        , count(pitwaiting.isAccepted) as waiting
        
    FROM proposed_indent as pi
    LEFT OUTER JOIN proposed_indent_transporter_mapping as pit on pi.proposedindentid = pit.proposedindentid
    INNER JOIN transporter as t on t.transporterid = pit.proposed_transporterid
    INNER JOIN depot as d on d.depotid = pi.depotid
    INNER JOIN zone as z on z.zoneid = d.zoneid
    INNER JOIN vehicletype as veh on pit.proposed_vehicletypeid = veh.vehicletypeid
    LEFT OUTER JOIN proposed_indent_transporter_mapping as pitplaced on pitplaced.pitmappingid = pit.pitmappingid and pitplaced.isAccepted = 1
    LEFT OUTER JOIN proposed_indent_transporter_mapping as pitrejected on pitrejected.pitmappingid = pit.pitmappingid and pitrejected.isAccepted = -1
    LEFT OUTER JOIN proposed_indent_transporter_mapping as pitwaiting on pitwaiting.pitmappingid = pit.pitmappingid and pitwaiting.isAccepted = 0
    WHERE (pi.customerno = custno OR custno IS NULL)
    AND (pit.proposed_transporterid = transporteridparam OR transporteridparam IS NULL)
    AND (pi.factoryid = factoryidparam OR factoryidparam IS NULL)
    AND (pi.depotid = depotidparam OR depotidparam IS NULL)
    AND (z.zoneid = zoneidparam OR zoneidparam IS NULL)
    AND (veh.skutypeid = typeidparam OR typeidparam IS NULL)
    AND (pi.date_required BETWEEN startdate AND enddate OR (startdate IS NULL AND enddate IS NULL))
    AND pi.isdeleted = 0
    group by pit.proposed_transporterid order by t.transportername;
		
END$$

DELIMITER ;
