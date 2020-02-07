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




DELIMITER $$
DROP PROCEDURE IF EXISTS get_zone_efficiency$$ 
CREATE PROCEDURE get_zone_efficiency(
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
		count(distinct(pi.proposedindentid)) as totalindent
        , pi.factoryid
		, f.factoryname		
        , pi.depotid 
        , d.depotname
        , z.zonename
        , t.transportername
        , count(piplaced.isApproved) as placed
        , count(pireject.isApproved) as reject
        , count(piwaiting.isApproved) as waiting
    FROM proposed_indent as pi
    LEFT OUTER JOIN proposed_indent_transporter_mapping as pit on pit.proposedindentid = pi.proposedindentid
	INNER JOIN transporter as t on t.transporterid = pit.proposed_transporterid
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
    group by  pi.factoryid,pit.proposed_transporterid,d.zoneid
    order by f.factoryname,t.transportername,z.zonename;
    
END$$
DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS get_transporter_zone_efficiency$$ 
CREATE PROCEDURE get_transporter_zone_efficiency(
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
		count(distinct(pi.proposedindentid)) as totalindent
		, pit.proposed_transporterid
        , t.transportername
        , z.zonename
        , z.zoneid
        , count(pitplaced.isAccepted) as placed
        , count(pitreject.isAccepted) as reject
        , count(pitwaiting.isAccepted) as waiting
    FROM proposed_indent as pi
    LEFT OUTER JOIN proposed_indent_transporter_mapping as pit ON pi.proposedindentid = pit.proposedindentid
    INNER JOIN transporter as t on t.transporterid = pit.proposed_transporterid
    INNER JOIN depot as d on d.depotid = pi.depotid
    INNER JOIN zone as z on z.zoneid = d.zoneid
    INNER JOIN vehicletype as veh on pit.proposed_vehicletypeid = veh.vehicletypeid
    LEFT OUTER JOIN proposed_indent_transporter_mapping as pitplaced on pitplaced.pitmappingid = pit.pitmappingid and pitplaced.isAccepted=1
    LEFT OUTER JOIN proposed_indent_transporter_mapping as pitreject on pitreject.pitmappingid = pit.pitmappingid and pitreject.isAccepted=-1
    LEFT OUTER JOIN proposed_indent_transporter_mapping as pitwaiting on pitwaiting.pitmappingid = pit.pitmappingid and pitwaiting.isAccepted=0
    WHERE (pit.customerno = custno OR custno IS NULL)
    AND (pit.proposed_transporterid = transporterparam OR transporterparam IS NULL)
    AND (pi.factoryid = factoryidparam OR factoryidparam IS NULL)
    AND (pi.depotid = depotidparam OR depotidparam IS NULL)
    AND (z.zoneid = zoneidparam OR zoneidparam IS NULL)
    AND (veh.skutypeid = typeidparam OR typeidparam IS NULL)
    AND (pi.date_required BETWEEN startdate AND enddate OR (startdate IS NULL AND enddate IS NULL))
AND pi.isdeleted = 0
    group by pit.proposed_transporterid order by t.transportername, z.zonename;
END$$
DELIMITER ;





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



DELIMITER $$
DROP PROCEDURE IF EXISTS get_daterequired_efficiency$$ 
CREATE PROCEDURE get_daterequired_efficiency(
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
		 (pi.date_required) as requireddate
        , count(pi.proposedindentid) as totalindent
        , count(piplaced.isApproved) as placed
        , count(pireject.isApproved) as reject
        , count(piwaiting.isApproved) as waiting
    FROM proposed_indent as pi
    LEFT OUTER JOIN proposed_indent_transporter_mapping as pit on pit.proposedindentid = pi.proposedindentid
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
    group by pi.date_required;
    
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_left_over_details`$$
CREATE PROCEDURE `get_left_over_details`(
        IN custno INT
        ,IN factoryidparam INT
        ,IN dateparam DATE
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
	SET custno = NULL;
    END IF;
    IF(factoryidparam = '' OR factoryidparam = 0) THEN
	SET factoryidparam = NULL;
    END IF;
    IF(dateparam = '' OR dateparam = 0) THEN
		SET dateparam = NULL;
    END IF;
    
    SELECT 	
            ld.leftoverid
            , f.factoryid
            , f.factoryname
            , d.depotid
            , d.depotname
            , daterequired
            , ld.weight
            , ld.volume
            , ld.customerno
    FROM    leftoverdetails ld
    INNER JOIN factory f ON f.factoryid = ld.factoryid
    INNER JOIN depot d ON d.depotid = ld.depotid
    WHERE (ld.customerno = custno OR custno IS NULL)
    AND (ld.factoryid = factoryidparam OR factoryidparam IS NULL)
    AND ((ld.daterequired = dateparam) OR dateparam IS NULL)
    AND   ld.isdeleted = 0;
END$$
DELIMITER ;


ALTER TABLE `leftoverdetails` CHANGE `daterequired` `daterequired` DATE NOT NULL;




-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 10, NOW(), 'Shrikant Suryawanshi','Placement Efficiency Is Deleted Changes ');
