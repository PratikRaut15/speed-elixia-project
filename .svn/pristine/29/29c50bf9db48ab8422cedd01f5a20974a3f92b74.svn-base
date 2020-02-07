ALTER TABLE `tmsmapping` ADD `updated_on` DATETIME NOT NULL AFTER `timestamp`;

DELIMITER $$
DROP PROCEDURE IF EXISTS get_assigned_transporter$$ 
CREATE PROCEDURE get_assigned_transporter(
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
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS get_pending_indent$$ 
CREATE PROCEDURE get_pending_indent(
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
		, f.factoryname
		, pi.depotid
		, d.depotname
		, pi.date_required
		, pit.proposed_vehicletypeid
		, v.vehiclecode as proposedvehiclecode
		, v.vehicledescription as proposedvehicledescription
		, veh.vehiclecode as actualvehiclecode
		, veh.vehicledescription as actualvehicledescription
	FROM proposed_indent as pi
	    INNER JOIN proposed_indent_transporter_mapping as pit on pit.proposedindentid = pi.proposedindentid and pit.isAccepted = 0
	    INNER JOIN factory as f on f.factoryid = pi.factoryid
	    INNER JOIN depot as d on d.depotid = pi.depotid
	    INNER JOIN vehicletype as v on v.vehicletypeid = pit.proposed_vehicletypeid
	    LEFT JOIN vehicletype as veh on veh.vehicletypeid = pit.actual_vehicletypeid
	WHERE (pi.customerno = custno OR custno IS NULL)
	    AND pi.hasTransporterAccepted = 0
	    AND (pi.date_required + INTERVAL 1 DAY ) <= dateparam
	    AND pi.isdeleted=0;   
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS get_distinct_transporters$$
CREATE PROCEDURE `get_distinct_transporters`( 
	IN custno int
)
BEGIN
	IF (custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    
	select 
    distinct(proposed_transporterid) 
    from proposed_indent_transporter_mapping as pit
	INNER JOIN proposed_indent as pi on  pi.proposedindentid = pit.proposedindentid
	WHERE (pit.customerno = custno OR custno IS NULL)
    AND pit.isdeleted = 0 
    AND pi.isdeleted = 0;

END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS get_transporter_indents$$ 
CREATE PROCEDURE get_transporter_indents(
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
		pi.proposedindentid
		, pi.factoryid
		, f.factoryname
		, pi.depotid
		, d.depotname
		, pi.date_required
		, pit.proposed_vehicletypeid
		, pit.actual_vehicletypeid
		, v.vehiclecode as proposedvehiclecode
		, v.vehicledescription as proposedvehicledescription
		, veh.vehiclecode as actualvehiclecode
		, veh.vehicledescription as actualvehicledescription
        , pit.isAccepted
        , t.transportername
        , pit.updated_on
	FROM proposed_indent_transporter_mapping as pit
    INNER JOIN proposed_indent as pi on pi.proposedindentid = pit.proposedindentid
    INNER JOIN transporter as t on t.transporterid = pit.proposed_transporterid
	INNER JOIN factory as f on f.factoryid = pi.factoryid
	INNER JOIN depot as d on d.depotid = pi.depotid
	INNER JOIN vehicletype as v on v.vehicletypeid = pit.proposed_vehicletypeid
	LEFT JOIN vehicletype as veh on veh.vehicletypeid = pit.actual_vehicletypeid
	WHERE (pit.customerno = custno OR custno IS NULL)
    AND (pit.proposed_transporterid = transporteridparam OR transporteridparam IS NULL)
	    AND pi.isdeleted=0
        AND pit.isdeleted=0
        order by f.factoryname; 
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS get_transporter_indent_count$$ 
CREATE PROCEDURE get_transporter_indent_count(
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


DELIMITER $$
DROP PROCEDURE IF EXISTS get_multiple_factory_officials$$
CREATE PROCEDURE `get_multiple_factory_officials`(
	IN custno INT,
    IN factoryidlist VARCHAR(100)
)
BEGIN
	
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    IF(factoryidlist = '') THEN
		SET factoryidlist = NULL;
    END IF;
    
    SELECT 
		f.factoryid
        , tm.userid
        , u.realname
        , u.username
        , u.email
        , u.phone
    FROM factory as f
    INNER JOIN tmsmapping as tm on tm.tmsid = f.factoryid and tm.role = 'factoryofficial'
    INNER JOIN speed.user as u on u.userid = tm.userid 
    WHERE (f.customerno = custno OR custno IS NULL)
    AND   (find_in_set(f.factoryid, factoryidlist) OR factoryidlist IS NULL)
    AND   u.isdeleted = 0
    AND   tm.isdeleted = 0
    AND   f.isdeleted = 0;
    
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS update_transporteractualshare$$
CREATE PROCEDURE update_transporteractualshare( 
    IN transid INT
    , IN factid INT
    , IN zid INT
    , IN sharedwt decimal(11,3)
    , IN totalwt decimal(11,3)
    , IN sharepercent decimal(6,2)
    , IN actshareidparam INT
    , IN custno INT
    , IN todaysdate DATETIME
    , IN userid INT
    )
BEGIN
    IF (sharedwt IS NULL AND totalwt IS NULL) THEN
        UPDATE transporter_actualshare
        SET      transporterid = transid
                , factoryid = factid
                , zoneid = zid
                , actualpercent = sharepercent
                , updated_on = todaysdate
                , updated_by= userid
        WHERE   actshareid= actshareidparam 
        AND     customerno = custno
        AND		isdeleted = 0;
    ELSE
        BEGIN
            DECLARE actualsharepercent DECIMAL(5,2);
            DECLARE tempsharedwt decimal(11,3);
            DECLARE temptotalwt decimal(11,3);
             
            SELECT shared_weight, total_weight
            INTO    tempsharedwt, temptotalwt
            FROM    transporter_actualshare
            WHERE   transporterid = transid
            AND     factoryid = factid
            AND     zoneid = zid
            AND     customerno = custno
            AND		isdeleted = 0;
             
            SET     tempsharedwt = tempsharedwt + sharedwt;
            SET     temptotalwt = temptotalwt + totalwt;
             
            SET     actualsharepercent = (tempsharedwt/temptotalwt) * 100;
             
            UPDATE transporter_actualshare
            SET     shared_weight = tempsharedwt
                    ,total_weight = temptotalwt
                    , actualpercent = actualsharepercent
                    , updated_on = todaysdate
                    , updated_by= userid
            WHERE   transporterid = transid
            AND     factoryid = factid
            AND     zoneid = zid
            AND     customerno = custno
            AND		isdeleted = 0;
        END;
    END IF;
END$$
DELIMITER ;



Create index index_proposedindentid on proposed_indent_transporter_mapping (proposedindentid);





-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 3, NOW(), 'Shrikant Suryawanshi','Transporter Official Changes');



