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
    group by pi.date_required;
    
END$$
DELIMITER ;





DELIMITER $$
DROP PROCEDURE IF EXISTS get_proposed_indent$$
CREATE PROCEDURE get_proposed_indent(
	IN custno INT
    	, IN propindentid INT
		, IN factoryidparam INT
        , IN transporteridparam INT
    	, IN daterequired varchar(15)
    	, IN isAcceptedparam INT
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
    IF(startdate = '' OR startdate = 0) THEN
		SET startdate = NULL;
    END IF;
    IF(enddate = '' OR enddate = 0) THEN
		SET enddate = NULL;
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
        AND (pi.date_required BETWEEN startdate AND enddate 
        OR (startdate IS NULL AND enddate IS NULL))
		AND pi.isdeleted = 0;
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS get_indent$$
CREATE PROCEDURE get_indent(
	IN custno INT
    , IN indentidparam INT
    , IN factoryidparam INT
    , IN startdate DATE
	, IN enddate DATE
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(indentidparam = '' OR indentidparam = 0) THEN
		SET indentidparam = NULL;
	END IF;
    IF(factoryidparam = '' OR factoryidparam = 0) THEN
		SET factoryidparam = NULL;
	END IF;
    IF(startdate = '' OR startdate = 0) THEN
		SET startdate = NULL;
    END IF;
    IF(enddate = '' OR enddate = 0) THEN
		SET enddate = NULL;
    END IF;
    
	SELECT 
    i.indentid,
    t.transporterid,
    t.transportername,
    i.vehicleno,
    i.proposedindentid,
    i.proposed_vehicletypeid,
    i.actual_vehicletypeid,
    i.factoryid,
    f.factoryname,
    i.depotid,
    d.depotname,
    vehtype.vehiclecode AS proposedvehiclecode,
    vehtype.vehicledescription as proposedvehicledescription,
    veh.vehiclecode AS actualvehiclecode,
    veh.vehicledescription as actualvehicledescription,
    i.date_required,
    i.loadstatus,
    shipmentno,
    i.remarks,
    totalweight,
    totalvolume,
    isdelivered,
    i.customerno,
    i.created_on,
    i.created_by,
    i.updated_on,
    i.updated_by
FROM
    indent i
        INNER JOIN
    transporter t ON t.transporterid = i.transporterid
        INNER JOIN
    factory f ON f.factoryid = i.factoryid
        INNER JOIN
    depot d ON d.depotid = i.depotid
        INNER JOIN
    vehicletype vehtype ON vehtype.vehicletypeid = i.proposed_vehicletypeid
        LEFT JOIN
    vehicletype veh ON veh.vehicletypeid = i.actual_vehicletypeid
       
WHERE
    (i.customerno = custno OR custno IS NULL)
        AND (i.indentid = indentidparam
        OR indentidparam IS NULL)
        AND (i.factoryid = factoryidparam
        OR factoryidparam IS NULL)
        AND (i.date_required BETWEEN startdate AND enddate 
        OR (startdate IS NULL AND enddate IS NULL))
        AND i.isdeleted = 0;
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS get_transporter_proposed_indent$$
CREATE PROCEDURE get_transporter_proposed_indent(
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




-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 9, NOW(), 'Shrikant Suryawanshi','Placement Efficiency & Placement Tracker Changes ');
