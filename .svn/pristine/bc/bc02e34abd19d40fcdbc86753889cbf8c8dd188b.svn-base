DELIMITER $$
DROP PROCEDURE IF EXISTS get_transporter_efficiency$$ 
CREATE PROCEDURE get_transporter_efficiency(
	IN custno INT 
)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    
    SELECT 
		distinct count(pit.proposed_transporterid) as totalindent
		, pit.proposed_transporterid
        , t.transportername
        , pit.isAccepted
        , ( SELECT 
			count(pitsub.isAccepted) 
            From proposed_indent_transporter_mapping as pitsub
			WHERE (pitsub.customerno = custno OR custno IS NULL)
            AND pitsub.proposed_transporterid = pit.proposed_transporterid 
            AND pitsub.isAccepted = 1
		  ) as placed
        , ( SELECT 
			count(pitsub.isAccepted) 
            From proposed_indent_transporter_mapping as pitsub
			WHERE (pitsub.customerno = custno OR custno IS NULL)
            AND pitsub.proposed_transporterid = pit.proposed_transporterid 
            AND pitsub.isAccepted = -1
		  ) as rejected  
		 , ( SELECT 
			count(pitsub.isAccepted) 
            From proposed_indent_transporter_mapping as pitsub
			WHERE (pitsub.customerno = custno OR custno IS NULL)
            AND pitsub.proposed_transporterid = pit.proposed_transporterid 
            AND pitsub.isAccepted = 0
		  ) as waiting
          
    FROM proposed_indent_transporter_mapping as pit
    INNER JOIN transporter as t on t.transporterid = pit.proposed_transporterid
    WHERE (pit.customerno = custno OR custno IS NULL)
    group by pit.proposed_transporterid;
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS get_zone_efficiency$$ 
CREATE PROCEDURE get_zone_efficiency(
	IN custno INT 
)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    
    SELECT 
		distinct count(pi.factoryid) as factoryidcount
        , pi.factoryid
		, f.factoryname		
        , pi.depotid 
        , d.depotname
        , z.zonename
        , ( SELECT 
			count(pisub.isApproved) 
            From proposed_indent as pisub
			WHERE (pisub.customerno = custno OR custno IS NULL)
            AND pisub.factoryid = pi.factoryid 
            AND pisub.isApproved = 1
		  ) as placed
          , ( SELECT 
			count(pisub.isApproved) 
            From proposed_indent as pisub
			WHERE (pisub.customerno = custno OR custno IS NULL)
            AND pisub.factoryid = pi.factoryid 
            AND pisub.isApproved = -1
		  ) as rejected
          
    FROM proposed_indent as pi
    INNER JOIN factory as f on f.factoryid = pi.factoryid
    INNER JOIN depot as d on d.depotid = pi.depotid
    INNER JOIN zone as z on z.zoneid = d.zoneid
    WHERE (pi.customerno = custno OR custno IS NULL)
    group by pi.depotid,pi.factoryid;
    
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS update_proposed_indent$$
CREATE PROCEDURE `update_proposed_indent`( 
	IN propindentid int
	, IN factoryid int
    , IN depotid int
	, IN total_weight decimal(7,3)
	, IN total_volume decimal(7,3)
	, IN isApprovedParam TINYINT(1)
	, IN hasTransAccepted TINYINT(1)
	, IN todaysdate DATETIME
    , IN userid INT
)
BEGIN

	IF	((propindentid IS NOT NULL && propindentid != '' && propindentid != '0')
		AND (factoryid IS NOT NULL && factoryid !='' && factoryid !='0')
		AND (depotid IS NOT NULL && depotid !='' && depotid !='0')
        AND (total_weight IS NOT NULL && total_weight !='')
        AND (total_volume IS NOT NULL && total_volume != '')) THEN
			UPDATE 	proposed_indent
			SET 	factoryid = factoryid
					, depotid = depotid
					, total_weight = total_weight
					, total_volume = total_volume
					, updated_on = todaysdate 
					, updated_by = userid
			WHERE	proposedindentid = propindentid;
    ELSEIF (hasTransAccepted IS NOT NULL) THEN 
			UPDATE 	proposed_indent
			SET 	hasTransporterAccepted = hasTransAccepted
					, updated_on = todaysdate 
					, updated_by = userid
			WHERE proposedindentid = propindentid;
    END IF;        
	IF (isApprovedParam IS NOT NULL) THEN
			UPDATE 	proposed_indent
			SET 	 isApproved = isApprovedParam
					, updated_on = todaysdate 
					, updated_by = userid
			WHERE	proposedindentid = propindentid;
            
	END IF ;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS get_transporter_zone_efficiency$$ 
CREATE PROCEDURE get_transporter_zone_efficiency(
	IN custno INT 
)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    
    SELECT 
		distinct count(pit.proposed_transporterid) as totalindent
		, pit.proposed_transporterid
        , t.transportername
        , pit.isAccepted
        , z.zonename
        , z.zoneid
        , ( SELECT 
			count(pitsub.isAccepted) 
            From proposed_indent_transporter_mapping as pitsub
            INNER JOIN proposed_indent as pisub ON pisub.proposedindentid = pitsub.proposedindentid
            INNER JOIN depot as dsub on dsub.depotid = pisub.depotid
            INNER JOIN zone as zsub on zsub.zoneid = dsub.zoneid
			WHERE (pitsub.customerno = custno OR custno IS NULL)
            AND pitsub.proposed_transporterid = pit.proposed_transporterid 
            AND zsub.zoneid = z.zoneid
            AND pitsub.isAccepted = 1
		  ) as placed
        , ( SELECT 
			count(pitsub.isAccepted) 
            From proposed_indent_transporter_mapping as pitsub
            INNER JOIN proposed_indent as pisub ON pisub.proposedindentid = pitsub.proposedindentid
            INNER JOIN depot as dsub on dsub.depotid = pisub.depotid
            INNER JOIN zone as zsub on zsub.zoneid = dsub.zoneid
			WHERE (pitsub.customerno = custno OR custno IS NULL)
            AND pitsub.proposed_transporterid = pit.proposed_transporterid 
            AND zsub.zoneid = z.zoneid
            AND pitsub.isAccepted = -1
		  ) as rejected  
		 , ( SELECT 
			count(pitsub.isAccepted) 
            From proposed_indent_transporter_mapping as pitsub
            INNER JOIN proposed_indent as pisub ON pisub.proposedindentid = pitsub.proposedindentid
            INNER JOIN depot as dsub on dsub.depotid = pisub.depotid
            INNER JOIN zone as zsub on zsub.zoneid = dsub.zoneid
			WHERE (pitsub.customerno = custno OR custno IS NULL)
            AND pitsub.proposed_transporterid = pit.proposed_transporterid 
            AND zsub.zoneid = z.zoneid
            AND pitsub.isAccepted = 0
		  ) as waiting
          
    FROM proposed_indent_transporter_mapping as pit
    INNER JOIN transporter as t on t.transporterid = pit.proposed_transporterid
    INNER JOIN proposed_indent as pi ON pi.proposedindentid = pit.proposedindentid
    INNER JOIN depot as d on d.depotid = pi.depotid
    INNER JOIN zone as z on z.zoneid = d.zoneid
    WHERE (pit.customerno = custno OR custno IS NULL)
    group by pit.proposed_transporterid, z.zonename;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS get_factory_efficiency$$ 
CREATE PROCEDURE get_factory_efficiency(
	IN custno INT 
)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    
    SELECT 
		distinct count(pi.factoryid) as factoryidcount
        , pi.factoryid
		, f.factoryname		
        , ( SELECT 
			count(pisub.isApproved) 
            From proposed_indent as pisub
			WHERE (pisub.customerno = custno OR custno IS NULL)
            AND pisub.factoryid = pi.factoryid 
            AND pisub.isApproved = 1
		  ) as placed
          , ( SELECT 
			count(pisub.isApproved) 
            From proposed_indent as pisub
			WHERE (pisub.customerno = custno OR custno IS NULL)
            AND pisub.factoryid = pi.factoryid 
            AND pisub.isApproved = -1
		  ) as rejected
          
    FROM proposed_indent as pi
    INNER JOIN factory as f on f.factoryid = pi.factoryid
    INNER JOIN depot as d on d.depotid = pi.depotid
    INNER JOIN zone as z on z.zoneid = d.zoneid
    WHERE (pi.customerno = custno OR custno IS NULL)
    group by pi.factoryid;
    
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS get_proposed_indent$$
CREATE PROCEDURE get_proposed_indent(
	IN custno INT
    , IN propindentid INT
    , IN factoryidparam INT
    , IN daterequired varchar(15)
    , IN isAcceptedparam INT 
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
        IF(daterequired = '' OR daterequired = 0) THEN
		SET daterequired = NULL;
	END IF;
    IF(isAcceptedparam = '' OR isAcceptedparam = 0) THEN
		SET isAcceptedparam = NULL;
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
    veh.vehiclecode AS actualvehiclecode,
    pit.vehicleno,
    pi.date_required,
    pit.proposed_vehicletypeid,
    pit.actual_vehicletypeid,
    pit.isAccepted,
    pit.vehicleno,
    pit.drivermobileno,
    total_weight,
    total_volume,
    pi.customerno,
    pi.created_on,
    pi.updated_on,
    pi.created_by,
    pi.updated_by,
    i.loadstatus,
    i.shipmentno,
    i.remarks
FROM
    proposed_indent pi
        INNER JOIN
    proposed_indent_transporter_mapping pit ON pit.proposedindentid = pi.proposedindentid
        INNER JOIN
    factory f ON f.factoryid = pi.factoryid
        INNER JOIN
    depot d ON d.depotid = pi.depotid
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
        AND (pi.date_required = daterequired
        OR daterequired IS NULL)
        AND (pit.isAccepted = isAcceptedparam
        OR isAcceptedparam IS NULL)
        AND pi.isdeleted = 0;
END$$
DELIMITER ;






