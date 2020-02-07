SET GLOBAL event_scheduler = ON;
DELIMITER $$
CREATE
EVENT `monthly_actualshare`
ON SCHEDULE EVERY 1 MONTH
STARTS '2015-08-01 00:00:00'
DO BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	  BEGIN
		-- ERROR
	  ROLLBACK;
	END;
	START TRANSACTION;
    BEGIN
		DECLARE actualsharehistid INT;
		CALL insert_transactualshare_history(@actualsharehistid);
		SELECT actualsharehistid INTO actualsharehistid;
		
		UPDATE 	actual_monthly_share 
		SET 	shared_weight = 0
				,total_weight = 0;
		
		COMMIT;
	END;
END$$

CREATE TABLE `transporter_actualshare` (
  `actshareid` int(11) NOT NULL AUTO_INCREMENT,
  `factoryid` int(11) NOT NULL,
  `zoneid` int(11) NOT NULL,
  `transporterid` int(11) NOT NULL,
  `shared_weight` decimal(11,3) NOT NULL,
  `total_weight` decimal(11,3) NOT NULL,
  `actualpercent` decimal(5,2) NOT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`actshareid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_transportershare`$$
CREATE  PROCEDURE `insert_transportershare`( 
	IN transporterid INT
	, IN factoryid INT
	, IN zoneid INT
    , IN sharepercent decimal(6, 2)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currenttransportershareid INT
	)
BEGIN
	INSERT INTO transportershare(
							transporterid
                            , factoryid
				, zoneid
                            , sharepercent
                            , customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						)
	VALUES ( 
				transporterid
                , factoryid
		, zoneid
                , sharepercent
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
	call insert_transporteractualshare(transporterid,factoryid, zoneid,sharepercent,customerno, todaysdate, userid);
	SET currenttransportershareid = LAST_INSERT_ID();
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_transporteractualshare`$$
CREATE PROCEDURE `insert_transporteractualshare`( 
	IN transporterid INT
	, IN factoryid INT
	, IN zoneid INT
	,IN sharepercent decimal(6, 2)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    
	)
BEGIN
	INSERT INTO `transporter_actualshare`(
						`factoryid`
						, `zoneid`
						, `transporterid`
						, `actualpercent`
						, `customerno`
						, `created_on`
						, `updated_on`
						, `created_by`
						, `updated_by`) 
				VALUES (factoryid
					,zoneid
					,transporterid
					,sharepercent
					,customerno
					,todaysdate
					,todaysdate
					,userid
					,userid
					);

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_transactualshare_history`$$
CREATE PROCEDURE `insert_transactualshare_history`(
	OUT actualsharehistid INT
)
BEGIN
	INSERT INTO transporter_actualshare_history	(`actshareid`
												, `factoryid`
												, `zoneid`
												, `transporterid`
												, `shared_weight`
												, `total_weight`
												, `actualpercent`
												, `customerno`
												, `created_on`
												, `updated_on`
												, `created_by`
												, `updated_by`
												, `isdeleted`
												, `insertedDate`)
	SELECT	 `actshareid`
			, `factoryid`
			, `zoneid`
			, `transporterid`
			, `shared_weight`
			, `total_weight`
			, `actualpercent`
			, `customerno`
			, `created_on`
			, `updated_on`
			, `created_by`
			, `updated_by`
			, `isdeleted`
            , now()
	FROM 	`transporter_actualshare`;
    
    SET actualsharehistid = LAST_INSERT_ID();

END$$
DELIMITER ;
    
DELIMITER $$
DROP PROCEDURE IF EXISTS `update_transporteractualshare`$$
CREATE PROCEDURE `update_transporteractualshare`( 
	IN transid INT
	, IN factid INT
	, IN zid INT
	, IN sharedwt decimal(11,3)
	, IN totalwt decimal(11,3)
	, IN custno INT
    , IN todaysdate DATETIME
    , IN userid INT
    
	)
BEGIN
    DECLARE actualsharepercent DECIMAL(5,2);
    DECLARE tempsharedwt decimal(11,3);
    DECLARE temptotalwt decimal(11,3);
    
    SELECT shared_weight, total_weight
	INTO 	tempsharedwt, temptotalwt
    FROM 	actual_monthly_share
    WHERE 	transporterid = transid
    AND		factoryid = factid
    AND 	zoneid = zid
    AND 	customerno = custno;
	
	SET 	tempsharedwt = tempsharedwt + sharedwt;
    SET		temptotalwt = temptotalwt + totalwt;
    
    SET		actualsharepercent = (tempsharedwt/temptotalwt) * 100;
    
	UPDATE `transporter_actualshare`
	SET 	`shared_weight` = shared_weight
			,`total_weight` = total_weight
            , `actualpercent` = actualsharepercent
			, `updated_on` = todaysdate
			, `updated_by`= userid
	WHERE 	transporterid = transid
    AND		factoryid = factid
    AND 	zoneid = zid
    AND 	customerno = custno;

END$$
DELIMITER ;


create table tmsmapping(
mid int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
userid int(11) NOT NULL,
role varchar(50) NOT NULL,
tmsid int(11) NOT NULL,
customerno  int(11) NOT NULL,
isdeleted tinyint(1),
timestamp datetime
);



DELIMITER $$
DROP PROCEDURE IF EXISTS `update_vehicletype`$$
CREATE PROCEDURE `update_vehicletype`( 
	IN vehiclecode VARCHAR(20)
	, IN vehicledescription VARCHAR (50)
	, IN tid INT (11)
    , IN volume decimal(7,3)
    , IN weight decimal(7,3)
	, IN vehtypeid INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE vehicletype
    SET  vehiclecode = vehiclecode
		, vehicledescription = vehicledescription
		, skutypeid = tid
        , volume = volume
		, weight = weight
        , updated_on = todaysdate
        , updated_by = userid
	WHERE vehicletypeid = vehtypeid;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `update_factory_delivery`$$
CREATE  PROCEDURE `update_factory_delivery`( 
	IN fdidparam int
	, IN factid int
	, IN skuid int
	, IN did int
	, IN daterequired date
	, IN weight decimal(7,3)
	, IN custno INT
	, IN todaysdate DATETIME
	, IN userid INT
	, IN isprocessed INT
)
BEGIN

	IF (isprocessed=1) THEN
		UPDATE 	factory_delivery 
		SET 	isProcessed = isprocessed 
		WHERE 	factoryid=factid 
		and 	depotid= did 
		and 	date_required = daterequired
		and     customerno = custno;
	ELSE	
		UPDATE 	factory_delivery
		SET 	factoryid=factid
				,skuid = skuid
				,depotid = did
				,date_required = daterequired
				,weight = weight
				,updated_on = todaysdate 
				, updated_by = userid
		WHERE 	fdid = fdidparam;
	END IF;

END$$
DELIMITER $$;


DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_proposed_indent`$$
CREATE PROCEDURE `insert_proposed_indent`( 
	IN factoryid int
	,IN depotid int 
	, IN total_weight float(7,3)
	, IN total_volume float(7,3)
	, IN daterequired date
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
DROP PROCEDURE IF EXISTS `get_transporter_proposed_indent`$$
CREATE PROCEDURE `get_transporter_proposed_indent`(
	IN custno INT
 , propindentid INT
    , transid INT
    
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

	SELECT 	        
			pit.pitmappingid	
			,pit.proposedindentid
			,pit.proposed_transporterid
			,pi.hasTransporterAccepted
			,t.transportername
			, pit.proposed_vehicletypeid
			, pit.actual_vehicletypeid
			, vehtype.vehiclecode as proposedvehiclecode
			, veh.vehiclecode as actualvehiclecode
			, pi.date_required
	       	        , pit.vehicleno
		        , pit.isAccepted
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
    
	WHERE 	(pit.customerno = custno OR custno IS NULL)
	AND		(pit.proposedindentid = propindentid OR propindentid IS NULL)
    AND		(pit.proposed_transporterid = transid OR transid IS NULL)
	
	AND 	pit.isdeleted = 0;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `update_proposed_indent`$$
CREATE PROCEDURE `update_proposed_indent`( 
	IN propindentid int,
	IN factoryid int,
    IN depotid int,
	IN total_weight decimal(7,3),
	IN total_volume decimal(7,3),
	IN isApproved TINYINT(1)
,IN hasTransAccepted TINYINT(1)
	, IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	
	IF (hasTransAccepted =1 )THEN 
	UPDATE proposed_indent
	SET 
		hasTransporterAccepted = hasTransAccepted
        
		, updated_on = todaysdate 
        ,updated_by = userid
	WHERE 
		proposedindentid = propindentid;
	
	ELSE 
UPDATE proposed_indent
	SET 
		factoryid = factoryid
        ,depotid = depotid
		,total_weight = total_weight
		,total_volume = total_volume
        ,isApproved = isApproved
		, updated_on = todaysdate 
        ,updated_by = userid
	WHERE 
		proposedindentid = propindentid;
END IF ;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_proposed_indent`$$
CREATE PROCEDURE `get_proposed_indent`(
	IN custno INT
    , propindentid INT
    , daterequired varchar(15)
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(propindentid = '' OR propindentid = 0) THEN
		SET propindentid = NULL;
	END IF;
IF(daterequired = '' OR daterequired = 0) THEN
		SET daterequired = NULL;
	END IF;
	SELECT 	        pi.proposedindentid
			,pi.factoryid
			, pi.hasTransporterAccepted
			, pi.isApproved
			, f.factoryname
			, pi.depotid
			, d.depotname
			, t.transporterid
			,t.transportername
	       	        , vehtype.vehiclecode
		        , pit.vehicleno
		        , pi.date_required
			, pit.proposed_vehicletypeid
			, pit.actual_vehicletypeid
			, total_weight
			, total_volume
			, pi.customerno
			, pi.created_on
			, pi.updated_on 
			, pi.created_by
			, pi.updated_by
	FROM 	proposed_indent pi
    INNER JOIN proposed_indent_transporter_mapping pit ON pit.proposedindentid = pi.proposedindentid
    INNER JOIN factory f ON f.factoryid = pi.factoryid
    INNER JOIN depot d ON d.depotid = pi.depotid
    INNER JOIN transporter t ON t.transporterid = pit.proposed_transporterid
    INNER JOIN vehicletype vehtype ON vehtype.vehicletypeid = pit.proposed_vehicletypeid
    
	WHERE 	(pi.customerno = custno OR custno IS NULL)
    AND		(pi.proposedindentid = propindentid OR propindentid IS NULL)
	AND	(pi.date_required = daterequired OR daterequired IS NULL)
	AND 	pi.isdeleted = 0;
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_indent`$$
CREATE PROCEDURE `insert_indent`( 
	IN transporterid int
	, IN vehicleno varchar(25)
	, IN proposedindentid int
	, IN proposed_vehicletypeid INT
	, IN actual_vehicletypeid INT
	, IN factoryid INT
	, IN depotid INT
	, IN date_required date
	
	
	, IN totalweight decimal(7,3)
	, IN totalvolume decimal(7,3)
	
	, IN customerno INT
	, IN todaysdate DATETIME
    , IN userid INT
    , OUT currentindentid INT
)
BEGIN

	INSERT INTO indent	( 
							transporterid
							, vehicleno
							, proposedindentid
							,proposed_vehicletypeid
							,actual_vehicletypeid
							, factoryid
							, depotid
							,date_required
							
							
							, totalweight
							, totalvolume
							
							, customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						) 
	VALUES 	( 
				transporterid                
                , vehicleno
                , proposedindentid
                ,proposed_vehicletypeid
		,actual_vehicletypeid
		, factoryid
		, depotid
		,date_required
		
                , totalweight
		, totalvolume
		
                , customerno
                , todaysdate
                , todaysdate
                , userid
                , userid
			);

	SET currentindentid = LAST_INSERT_ID();

END$$
DELIMITER ;


ALTER TABLE `indent` CHANGE `vehicleid` `vehicleno` VARCHAR(25) NOT NULL;

ALTER TABLE indent add proposed_vehicletypeid int(11) NOT NULL AFTER transporterid;
ALTER TABLE indent add actual_vehicletypeid int(11) NOT NULL AFTER proposed_vehicletypeid;
ALTER TABLE `indent` DROP `indent_sku_mappingid`;
ALTER TABLE `indent` CHANGE `totalweight` `totalweight` DECIMAL(7,3) NULL DEFAULT NULL;
ALTER TABLE `indent` CHANGE `totalvolume` `totalvolume` DECIMAL(7,3) NULL DEFAULT NULL;
ALTER TABLE `indent` CHANGE `Proposedindentid` `proposedindentid` INT(11) NOT NULL;

ALTER TABLE indent add date_required date NOT NULL AFTER proposedindentid;
ALTER TABLE indent add actual_deliverydate date NOT NULL AFTER date_required;

ALTER TABLE indent add factoryid int(11) NOT NULL AFTER actual_vehicletypeid;
ALTER TABLE indent add depotid int(11) NOT NULL AFTER factoryid;

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_indent`$$
CREATE  PROCEDURE `get_indent`(
	IN custno INT
    , IN indentidparam INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(indentidparam = '' OR indentidparam = 0) THEN
		SET indentidparam = NULL;
	END IF;
    
	SELECT 	i.indentid 	
		, t.transporterid
		 ,t.transportername
			, i.vehicleno
            , i.proposedindentid
            , i.proposed_vehicletypeid
	    , i.actual_vehicletypeid
	    , i.factoryid
		, f.factoryname			
	,i.depotid
	,d.depotname
, vehtype.vehiclecode as proposedvehiclecode
			, veh.vehiclecode as actualvehiclecode
	,i.date_required			
            , shipmentno
            , totalweight
            , totalvolume
            , isdelivered
            , ism.skuid
            , ism.no_of_units
            , s.sku_description
            , i.customerno
	FROM 	indent i
    INNER JOIN transporter t ON t.transporterid = i.transporterid
    INNER JOIN factory f ON f.factoryid=i.factoryid	
    INNER JOIN depot d ON d.depotid=i.depotid	
    INNER JOIN vehicletype vehtype ON vehtype.vehicletypeid = i.proposed_vehicletypeid
    left JOIN vehicletype veh ON veh.vehicletypeid = i.actual_vehicletypeid
    left JOIN indent_sku_mapping ism ON ism.indentid = i.indentid
    left JOIN sku s ON s.skuid = ism.skuid
	WHERE 	(i.customerno = custno OR custno IS NULL)
	AND		(i.indentid = indentidparam OR indentidparam IS NULL)
	AND 	i.isdeleted = 0;
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `get_skuweight_byfactorydepot`$$
CREATE PROCEDURE `get_skuweight_byfactorydepot`(
    IN custno INT
    , IN datereq datetime
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
        SET custno = NULL;
    END IF;
    IF(datereq = '' OR datereq LIKE '%0000-00-00%') THEN
        SET datereq = NULL;
    END IF;
    SELECT     sum(fd.weight) as weight
            , fd.date_required
            , fd.factoryid
            , fd.depotid
            FROM factory_delivery fd
        WHERE     (customerno = custno OR custno IS NULL)
        AND        (fd.date_required = datereq OR datereq IS NULL)
	AND 	  (fd.isProcessed=0)
        GROUP BY date_required, factoryid, depotid;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS  `get_skuweight`$$
CREATE PROCEDURE `get_skuweight`(
    IN custno INT
    , IN did INT
    , IN factid INT
    , IN datereq datetime
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
        SET custno = NULL;
    END IF;
    IF(did = '' OR did = 0) THEN
        SET did = NULL;
    END IF;
    IF(factid = '' OR factid = 0) THEN
        SET factid = NULL;
    END IF;
    IF(datereq = '' OR datereq LIKE '%0000-00-00%') THEN
        SET datereq = NULL;
    END IF;
    SELECT     fd.skuid
            , s.skucode
	    , s.sku_description
	    , s.skutypeid
	    , s.weight as unitweight
            , s.volume as unitvolume
            , sum(fd.weight) as skuweight
            , fd.date_required
            , fd.depotid
            , fd.factoryid
    FROM     factory_delivery fd INNER JOIN sku s ON fd.skuid = s.skuid
    WHERE     (fd.customerno = custno OR custno IS NULL)
    AND        (fd.factoryid = factid OR factid IS NULL)
    AND        (fd.depotid = did OR did IS NULL)
    AND        (fd.date_required = datereq OR datereq IS NULL)
    AND       (fd.isProcessed=0)
    GROUP BY date_required,factoryid, depotid, skuid;

END$$
DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `get_sku`$$
CREATE PROCEDURE `get_sku`(
	IN custno INT
    , IN skuidparam INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF (skuidparam = '' OR skuidparam = 0) THEN
		SET skuidparam = NULL;
    END IF;
	SELECT 	skucode
			, skuid
			, sku_description
			, st.type
			, st.tid
			, volume
			, weight
			, sku.customerno
	FROM 	sku
	LEFT JOIN skutypes as st on st.tid = sku.skutypeid
	WHERE 	(sku.customerno = custno OR custno IS NULL)
    AND		(skuid = skuidparam OR skuidparam IS NULL)
	AND 	sku.isdeleted = 0;
END$$
DELIMITER ;


