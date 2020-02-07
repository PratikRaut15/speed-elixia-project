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
        , count(pitplaced.isAccepted) as placed
        , count(pitrejected.isAccepted) as rejected
        , count(pitwaiting.isAccepted) as waiting
        
    FROM proposed_indent_transporter_mapping as pit
    INNER JOIN transporter as t on t.transporterid = pit.proposed_transporterid
    LEFT OUTER JOIN proposed_indent_transporter_mapping as pitplaced on pitplaced.pitmappingid = pit.pitmappingid and pitplaced.isAccepted = 1
    LEFT OUTER JOIN proposed_indent_transporter_mapping as pitrejected on pitrejected.pitmappingid = pit.pitmappingid and pitrejected.isAccepted = -1
    LEFT OUTER JOIN proposed_indent_transporter_mapping as pitwaiting on pitwaiting.pitmappingid = pit.pitmappingid and pitwaiting.isAccepted = 0
    WHERE (pit.customerno = custno OR custno IS NULL)
    group by pit.proposed_transporterid order by t.transportername;
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
        , count(piplaced.isApproved) as placed
        , count(pireject.isApproved) as reject
        , count(piwaiting.isApproved) as waiting
    FROM proposed_indent as pi
    INNER JOIN factory as f on f.factoryid = pi.factoryid
    INNER JOIN depot as d on d.depotid = pi.depotid
    INNER JOIN zone as z on z.zoneid = d.zoneid
    LEFT OUTER JOIN proposed_indent as piplaced on piplaced.proposedindentid = pi.proposedindentid and piplaced.isApproved=1
    LEFT OUTER JOIN proposed_indent as pireject on pireject.proposedindentid = pi.proposedindentid and pireject.isApproved=-1
    LEFT OUTER JOIN proposed_indent as piwaiting on piwaiting.proposedindentid = pi.proposedindentid and piwaiting.isApproved=0
    WHERE (pi.customerno = custno OR custno IS NULL)
    group by pi.depotid,pi.factoryid order by z.zonename;
    
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
        , count(pitplaced.isAccepted) as placed
        , count(pitreject.isAccepted) as reject
        , count(pitwaiting.isAccepted) as waiting
    FROM proposed_indent_transporter_mapping as pit
    INNER JOIN transporter as t on t.transporterid = pit.proposed_transporterid
    INNER JOIN proposed_indent as pi ON pi.proposedindentid = pit.proposedindentid
    INNER JOIN depot as d on d.depotid = pi.depotid
    INNER JOIN zone as z on z.zoneid = d.zoneid
    LEFT OUTER JOIN proposed_indent_transporter_mapping as pitplaced on pitplaced.pitmappingid = pit.pitmappingid and pitplaced.isAccepted=1
    LEFT OUTER JOIN proposed_indent_transporter_mapping as pitreject on pitreject.pitmappingid = pit.pitmappingid and pitreject.isAccepted=-1
    LEFT OUTER JOIN proposed_indent_transporter_mapping as pitwaiting on pitwaiting.pitmappingid = pit.pitmappingid and pitwaiting.isAccepted=0
    WHERE (pit.customerno = custno OR custno IS NULL)
    group by pit.proposed_transporterid order by t.transportername, z.zonename;
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
        , count(piplaced.isApproved) as placed
        , count(pireject.isApproved) as reject
        , count(piwaiting.isApproved) as waiting
          
    FROM proposed_indent as pi
    INNER JOIN factory as f on f.factoryid = pi.factoryid
    INNER JOIN depot as d on d.depotid = pi.depotid
    INNER JOIN zone as z on z.zoneid = d.zoneid
    LEFT OUTER JOIN proposed_indent as piplaced on piplaced.proposedindentid = pi.proposedindentid and piplaced.isApproved=1
    LEFT OUTER JOIN proposed_indent as pireject on pireject.proposedindentid = pi.proposedindentid and pireject.isApproved=-1
    LEFT OUTER JOIN proposed_indent as piwaiting on piwaiting.proposedindentid = pi.proposedindentid and piwaiting.isApproved=0
    WHERE (pi.customerno = custno OR custno IS NULL)
    group by pi.factoryid order by f.factoryname;
    
END$$
DELIMITER ;






DELIMITER $$
DROP PROCEDURE IF EXISTS `update_transportershare`$$
CREATE PROCEDURE `update_transportershare`(
    IN transhareid INT
    , IN transporteridparam INT
    , IN factoryidparam INT
    , IN zoneidparam INT
    , IN sharepercent decimal(6, 2)
    , IN todaysdate DATETIME
    , IN userid INT
    )
BEGIN
    DECLARE custno INT;
    DECLARE actshareidparam INT;
     
    UPDATE  transportershare
    SET     transporterid = transporteridparam
            , factoryid = factoryidparam
            , zoneid = zoneidparam
            , sharepercent = sharepercent
            , updated_on = todaysdate
            , updated_by = userid
    WHERE   transportershareid = transhareid;
     
    SELECT  customerno 
    INTO    custno
    FROM    transportershare
    WHERE   transportershareid = transhareid;
     
    SELECT  actshareid
    INTO    actshareidparam
    FROM    transporter_actualshare
    WHERE   transporterid = transporteridparam
    AND     factoryid = factoryidparam
    AND     zoneid = zoneidparam
    AND     customerno = custno;
     
    IF (actshareidparam IS NULL OR actshareidparam = '' OR actshareidparam = 0) THEN
        CALL insert_transporteractualshare(transporteridparam, factoryidparam, zoneidparam, sharepercent, custno, todaysdate, userid);
    ELSE
        CALL update_transporteractualshare(transporteridparam, factoryidparam, zoneidparam, NULL, NULL, sharepercent, actshareidparam, custno, todaysdate, userid);
    END IF;
END$$
DELIMITER ;
​
DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_transportershare`$$
CREATE PROCEDURE `delete_transportershare`(
	IN currenttransportershareid INT
    , IN currenttransporterid INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
    DECLARE custno INT;
    DECLARE actshareidparam INT;
    DECLARE transporteridparam INT;
    DECLARE factoryidparam INT;
    DECLARE zoneidparam INT;
    
	IF (currenttransportershareid = '' OR currenttransportershareid = 0) THEN
		SET currenttransportershareid = NULL;
    END IF;
    
    IF (currenttransporterid = '' OR currenttransporterid = 0) THEN
		SET currenttransporterid = NULL;
    END IF;
    
	UPDATE transportershare 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE 	(transportershareid = currenttransportershareid OR currenttransportershareid IS NULL)
    AND		(transporterid = currenttransporterid OR currenttransporterid IS NULL);
​
	IF(currenttransportershareid IS NOT NULL) THEN
		SELECT  customerno, transporterid, factoryid, zoneid
		INTO    custno, transporteridparam, factoryidparam, zoneidparam
		FROM    transportershare
		WHERE   transportershareid = currenttransportershareid;
		 
		SELECT  actshareid
		INTO    actshareidparam
		FROM    transporter_actualshare
		WHERE   transporterid = transporteridparam
		AND     factoryid = factoryidparam
		AND     zoneid = zoneidparam
		AND     customerno = custno;
		
		IF (actshareidparam IS NOT NULL) THEN
			CALL delete_transporteractualshare(transporteridparam, actshareidparam, custno, todaysdate, userid);
		END IF;
	ELSEIF(currenttransporterid IS NOT NULL) THEN
		CALL delete_transporteractualshare(transporteridparam, NULL, custno, todaysdate, userid);
	END IF;
END$$
DELIMITER ;
​
​
​
DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_transporteractualshare`$$
CREATE PROCEDURE `delete_transporteractualshare`( 
    IN transid INT
    , IN actshareidparam INT
    , IN custno INT
    , IN todaysdate DATETIME
    , IN userid INT
    )
BEGIN
        UPDATE `transporter_actualshare`
        SET     isdeleted = 1
                , `updated_on` = todaysdate
                , `updated_by`= userid
        WHERE  	(actshareid = actshareidparam OR actshareidparam IS NULL)
        AND 	(transporterid = transid OR transid IS NULL)
        AND     customerno = custno;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_factory_delivery`$$
CREATE PROCEDURE `get_factory_delivery`(
        IN custno INT
        , IN fdidparam INT
        , IN factoryidparam INT
        , IN depotidparam INT
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    IF(fdidparam = '' OR fdidparam = 0) THEN
		SET fdidparam = NULL;
    END IF;
    IF(factoryidparam = '' OR factoryidparam = 0) THEN
		SET factoryidparam = NULL;
    END IF;
    IF(depotidparam = '' OR depotidparam = 0) THEN
		SET depotidparam = NULL;
    END IF;

    SELECT 	
            f.factoryid
            , fd.fdid
            , f.factoryname
            , s.skuid
            , s.skucode
            , s.sku_description
            , d.depotid
            , d.depotname
            , date_required
            , fd.netWeight
            , fd.grossWeight
            , fd.customerno
            , fd.created_on
            , fd.updated_on
    FROM    factory_delivery fd
    INNER JOIN factory f ON f.factoryid = fd.factoryid
    INNER JOIN sku s ON s.skuid = fd.skuid
    INNER JOIN depot d ON d.depotid = fd.depotid
    WHERE (fd.customerno = custno OR custno IS NULL)
    AND   (fdid = fdidparam OR fdidparam IS NULL)
    AND   (fd.factoryid = factoryidparam OR factoryidparam IS NULL)
    AND   (fd.depotid = depotidparam OR depotidparam IS NULL)
    AND   fd.isdeleted = 0;
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS get_indent$$
CREATE PROCEDURE get_indent(
	IN custno INT
    , IN indentidparam INT
    , IN factoryidparam INT
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
    veh.vehiclecode AS actualvehiclecode,
    i.date_required,
    i.loadstatus,
    shipmentno,
    i.remarks,
    totalweight,
    totalvolume,
    isdelivered,
    ism.skuid,
    ism.no_of_units,
    s.sku_description,
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
        LEFT JOIN
    indent_sku_mapping ism ON ism.indentid = i.indentid
        LEFT JOIN
    sku s ON s.skuid = ism.skuid
WHERE
    (i.customerno = custno OR custno IS NULL)
        AND (i.indentid = indentidparam
        OR indentidparam IS NULL)
        AND (i.factoryid = factoryidparam
        OR factoryidparam IS NULL)
        AND i.isdeleted = 0;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS get_left_over_details$$
CREATE PROCEDURE get_left_over_details(
        IN custno INT
        ,IN factoryidparam INT
        ,IN daterequiredparam date
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    IF(factoryidparam = '' OR factoryidparam = 0) THEN
		SET factoryidparam = NULL;
    END IF;
    IF(daterequiredparam = '' OR daterequiredparam = 0) THEN
		SET daterequiredparam = NULL;
    END IF;
    
    SELECT 	
            ld.leftoverid
            , f.factoryid
            , f.factoryname
            , d.depotid
            , d.depotname
            , ld.daterequired
            , ld.weight
            , ld.volume
            , ld.customerno
            , ld.created_on
            , ld.created_by
            , ld.updated_on
            , ld.updated_by
    FROM    leftoverdetails ld
    INNER JOIN factory f ON f.factoryid = ld.factoryid
    INNER JOIN depot d ON d.depotid = ld.depotid
    WHERE (ld.customerno = custno OR custno IS NULL)
    AND   (ld.factoryid = factoryidparam OR factoryidparam IS NULL)
    AND   (DATE(ld.daterequired) = daterequiredparam OR daterequiredparam IS NULL)
	AND   ld.isProcessed = 0
    AND   ld.isdeleted = 0;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_skuweight_byfactorydepot`$$
CREATE  PROCEDURE `get_skuweight_byfactorydepot`(
    IN custno INT
    , IN datereq datetime
    , IN factoryidparam INT
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
        SET custno = NULL;
    END IF;
    IF(datereq = '' OR datereq LIKE '%0000-00-00%') THEN
        SET datereq = NULL;
    END IF;
    IF(factoryidparam = '' OR factoryidparam = 0) THEN
        SET factoryidparam = NULL;
    END IF;
    SELECT     sum(fd.grossWeight) as weight
            , fd.date_required
            , fd.factoryid
            , fd.depotid
	FROM 	factory_delivery fd
	WHERE   (customerno = custno OR custno IS NULL)
	AND     (fd.date_required = datereq OR datereq IS NULL)
    AND     (fd.factoryid = factoryidparam OR factoryidparam IS NULL)
	AND 	(fd.isProcessed=0)
	GROUP BY date_required, factoryid, depotid;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_proposed_indent`$$
CREATE PROCEDURE `insert_proposed_indent`( 
	IN factoryid int
	,IN depotid int 
	, IN total_weight float(7,3)
	, IN total_volume float(7,3)
	, IN daterequired date
    , IN remark varchar(250)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentproposedindentid INT
)
BEGIN

	INSERT INTO proposed_indent( 
					factoryid
					, depotid	
                    , total_weight
                    , total_volume
					, date_required
                    , remark
                    , customerno
                    , created_on
                    , updated_on 
                    , created_by
                    , updated_by
                    ) 
	VALUES 	( 
				factoryid
				, depotid
                , total_weight
                , total_volume
				, daterequired
                , remark
                , customerno
                , todaysdate
                , todaysdate
                , userid
                , userid
			);

	SET currentproposedindentid = LAST_INSERT_ID();

	call update_factory_delivery(0,factoryid,0,depotid, daterequired,'',customerno,todaysdate,userid,1);

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


