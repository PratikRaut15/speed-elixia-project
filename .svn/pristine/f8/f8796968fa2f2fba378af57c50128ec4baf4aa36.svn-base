/*
	Name			- get_factory_efficiency
    Description 	-	To get factory efficiency
    Parameters		-	customernoparam, factoryid, depotid, transporteid, zoneid, typeid, startdate, enddate
    Module			-TMS
    Sub-Modules 	- 	Placement Efficiency
    Sample Call		-	call get_factory_efficiency('116','','','','','','2015-12-01','2015-12-31');

    Created by		-	Shrikant 
    Created	on		- Nov, 2015
    Change details 	-
    1) 	Updated by	-	Shrikant Suryawanshi
	Updated	on	-	17 Dec 2015 
        Reason		-	Add startdate, enddate, zoneid, typeid for filtering data
    2)  Updated by	-	Shrikant Suryawanshi
	Updated	on	-	21 Dec 2015 
        Reason		-	to check isdeleted records 
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS get_factory_efficiency$$ 
CREATE PROCEDURE get_factory_efficiency(
	IN custno INT,
    IN factoryidparam INT,
    IN depotidparam INT,
    IN transporterparam INT,
    IN zoneidparam INT,
	IN typeidparam INT,
    IN startdate DATE,
    IN enddate DATE	
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
    IF(transporterparam = '' OR transporterparam = 0) THEN
		SET transporterparam = NULL;
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
		count(pi.proposedindentid) as totalindent
        , pi.factoryid
		, f.factoryname		
        , count(piplaced.isApproved) as placed
        , count(pireject.isApproved) as reject
        , count(piwaiting.isApproved) as waiting
          
    FROM proposed_indent as pi
    LEFT OUTER JOIN proposed_indent_transporter_mapping as pit on pit.proposedindentid = pi.proposedindentid
    INNER JOIN factory as f on f.factoryid = pi.factoryid
    INNER JOIN depot as d on d.depotid = pi.depotid
    INNER JOIN zone as z on z.zoneid = d.zoneid
    INNER JOIN vehicletype as veh on pit.proposed_vehicletypeid = veh.vehicletypeid
    LEFT OUTER JOIN proposed_indent as piplaced on piplaced.proposedindentid = pi.proposedindentid and piplaced.isApproved=1
    LEFT OUTER JOIN proposed_indent as pireject on pireject.proposedindentid = pi.proposedindentid and pireject.isApproved=-1
    LEFT OUTER JOIN proposed_indent as piwaiting on piwaiting.proposedindentid = pi.proposedindentid and piwaiting.isApproved=0
    WHERE (pi.customerno = custno OR custno IS NULL)
    AND (pi.factoryid = factoryidparam OR factoryidparam IS NULL)
    AND (pi.depotid = depotidparam OR depotidparam IS NULL)
    AND (pit.proposed_transporterid = transporterparam OR transporterparam IS NULL)
    AND (z.zoneid = zoneidparam OR zoneidparam IS NULL)
    AND (veh.skutypeid = typeidparam OR typeidparam IS NULL)
    AND (pi.date_required BETWEEN startdate AND enddate OR (startdate IS NULL AND enddate IS NULL))
    AND pi.isdeleted = 0
    group by pi.factoryid order by f.factoryname;
    
END$$
DELIMITER ;
