DELIMITER $$
DROP PROCEDURE IF EXISTS get_factory_officials$$ 
CREATE PROCEDURE get_factory_officials(
	IN custno INT,
    IN factoryidparam INT
)
BEGIN
	
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    IF(factoryidparam = '' OR factoryidparam = 0) THEN
		SET factoryidparam = NULL;
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
    AND   (f.factoryid = factoryidparam OR factoryidparam IS NULL)
    AND   u.isdeleted = 0
    AND   tm.isdeleted = 0
    AND   f.isdeleted = 0;
    
    
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
        , v.vehiclecode
	FROM proposed_indent as pi
    INNER JOIN proposed_indent_transporter_mapping as pit on pit.proposedindentid = pi.proposedindentid and pit.isAccepted = 0
    INNER JOIN factory as f on f.factoryid = pi.factoryid
    INNER JOIN depot as d on d.depotid = pi.depotid
    INNER JOIN vehicletype as v on v.vehicletypeid = pit.proposed_vehicletypeid
	WHERE (pi.customerno = custno OR custno IS NULL)
    AND pi.hasTransporterAccepted = 0
    AND (pi.date_required + INTERVAL 1 DAY + INTERVAL -1 SECOND) <= dateparam
    AND pi.isdeleted=0;
    
    
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
DROP PROCEDURE IF EXISTS `update_pit_mapping`$$
CREATE  PROCEDURE `update_pit_mapping`( 
	IN pitmapid INT
    , IN actual_vehtypeid INT
    , IN vehicleno varchar(20)
    , IN drivermobileno varchar(12)
    , IN isAccepted tinyint(1)
    , IN remarksParam varchar(250)
	, IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE 	proposed_indent_transporter_mapping
	SET 	actual_vehicletypeid = actual_vehtypeid
            , vehicleno = vehicleno
			, drivermobileno = drivermobileno
            , isAccepted = isAccepted
            , remarks =  COALESCE(remarksParam, remarks)
			, updated_on = todaysdate 
            , updated_by = userid
	WHERE 	pitmappingid = pitmapid;

END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS get_autoreject_indent$$ 
CREATE PROCEDURE get_autoreject_indent(
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
		, pi.depotid
        , pit.proposed_transporterid
        , pit.proposed_vehicletypeid
        , pit.vehicleno
        , pit.pitmappingid
        , pit.drivermobileno
        , pit.isAccepted
        , pit.remarks
        , pit.actual_vehicletypeid
	FROM proposed_indent as pi
    INNER JOIN proposed_indent_transporter_mapping as pit on pit.proposedindentid = pi.proposedindentid and pit.isAccepted = 0 
    WHERE (pi.customerno = custno OR custno IS NULL)
    AND pi.hasTransporterAccepted = 0
    AND pit.created_on < dateparam
    AND pi.isdeleted=0;
    
    
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS delete_proposed_indent$$
CREATE  PROCEDURE `delete_proposed_indent`( 
	IN propindentid int
    , IN remarkParam varchar(250)
    , IN todaysdate DATETIME
    , IN userid INT
    
)
BEGIN
	UPDATE 	proposed_indent
	SET 	isdeleted = 1
			,remark =  COALESCE(remarkParam, remark)
			,updated_on = todaysdate
            ,updated_by = userid
	WHERE 	proposedindentid = propindentid;
	CALL delete_pit_mapping(NULL,propindentid,todaysdate,userid);
END$$
DELIMITER ;


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
        , t.transportermail
        , t.transportermobileno
	FROM proposed_indent as pi
    INNER JOIN proposed_indent_transporter_mapping as pit on pit.proposedindentid = pi.proposedindentid and pit.isAccepted = 0
    INNER JOIN factory as f on f.factoryid = pi.factoryid
    INNER JOIN depot as d on d.depotid = pi.depotid
    INNER JOIN vehicletype as v on v.vehicletypeid = pit.proposed_vehicletypeid
    INNER JOIN transporter as t on t.transporterid = pit.proposed_transporterid
    WHERE (pi.customerno = custno OR custno IS NULL)
    AND (pi.proposedindentid = proposedindentidparam OR proposedindentidparam IS NULL)
    AND pi.hasTransporterAccepted = 0
    AND pi.isdeleted=0;
    
    
END$$
DELIMITER ;


/*****************************************************************************************/




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
    AND pi.isdeleted=0;
    AND u.isdeleted = 0;
    AND tm.isdeleted = 0;
		
    
    
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS get_factory_officials$$ 
CREATE PROCEDURE get_factory_officials(
	IN custno INT,
    IN factoryidparam INT
)
BEGIN
	
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    IF(factoryidparam = '' OR factoryidparam = 0) THEN
		SET factoryidparam = NULL;
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
    AND   (f.factoryid = factoryidparam OR factoryidparam IS NULL)
    AND   u.isdeleted = 0
    AND   tm.isdeleted = 0
    AND   f.isdeleted = 0;
    
    
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS get_transporter_officials$$ 
CREATE PROCEDURE get_transporter_officials(
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
		t.transporterid
        , tm.userid
        , u.realname
        , u.username
        , u.email
        , u.phone
    FROM transporter as t
    INNER JOIN tmsmapping as tm on tm.tmsid = t.transporterid and tm.role = 'transporter'
    INNER JOIN speed.user as u on u.userid = tm.userid 
    WHERE (t.customerno = custno OR custno IS NULL)
    AND   (t.transporterid = transporteridparam OR transporteridparam IS NULL)
    AND   u.isdeleted = 0
    AND   tm.isdeleted = 0
    AND   t.isdeleted = 0;
    
    
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS get_autoreject_indent$$ 
CREATE PROCEDURE get_autoreject_indent(
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
		, pi.depotid
        , pit.proposed_transporterid
        , pit.proposed_vehicletypeid
        , pit.vehicleno
        , pit.pitmappingid
        , pit.drivermobileno
        , pit.isAccepted
        , pit.remarks
        , pit.actual_vehicletypeid
        , pi.date_required
	, f.factoryname
	, d.depotname
	FROM proposed_indent as pi
    INNER JOIN proposed_indent_transporter_mapping as pit on pit.proposedindentid = pi.proposedindentid and pit.isAccepted = 0
    INNER JOIN depot as d on d.depotid = pi.depotid
    INNER JOIN factory as f on f.factoryid = pi.factoryid
    INNER JOIN vehicletype as v on v.vehicletypeid = pit.proposed_vehicletypeid	 
    WHERE (pi.customerno = custno OR custno IS NULL)
    AND pi.hasTransporterAccepted = 0
    AND pit.created_on < dateparam
    AND pi.isdeleted=0;
    
    
END$$
DELIMITER ;


