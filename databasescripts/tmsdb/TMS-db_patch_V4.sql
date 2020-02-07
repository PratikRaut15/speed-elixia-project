DROP TABLE IF EXISTS transporter_actualshare_history;
CREATE TABLE `transporter_actualshare_history` (
  `tshid` int(11) NOT NULL AUTO_INCREMENT,
  `actshareid` int(11) NOT NULL,
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
  `insertedDate` datetime,
  PRIMARY KEY (`tshid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_trans_actualshare_history`$$
CREATE PROCEDURE `insert_trans_actualshare_history`(
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
	FROM 	`actual_monthly_share`;
    
    SET actualsharehistid = LAST_INSERT_ID();

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

	call update_factory_delivery(0,factoryid,0,depotid, daterequired,'','',customerno,todaysdate,userid,1);

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_proposed_indent_sku_mapping`$$
CREATE PROCEDURE `insert_proposed_indent_sku_mapping`( 
	IN proposedindentid int
	, IN skuid int 
    , IN no_of_units INT
	, IN weight decimal(11,3)
	, IN volume decimal(11,3)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    
)
BEGIN
		INSERT INTO `proposed_indent_sku_mapping`
		(
			`proposedindentid`
			,`skuid`
			,`no_of_units`
			,`weight`
			,`volume`
			,`customerno`
			,`created_on`
			,`updated_on`
			,`created_by`
			,`updated_by`
		)
		VALUES
		(
				proposedindentid
				, skuid
                , no_of_units
                , weight
				, volume
                , customerno
                , todaysdate
                , todaysdate
                , userid
                , userid
			);

	

END$$
DELIMITER ;		


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_transporteractualshare`$$
CREATE  PROCEDURE `get_transporteractualshare`(
	IN custno INT
    , IN currenttransporterid INT
    , IN currentfactid INT
    , IN currentzoneid INT
    , IN transhareid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(currenttransporterid = '' OR currenttransporterid = 0) THEN
		SET currenttransporterid = NULL;
	END IF;
IF(currentfactid = '' OR currentfactid = 0) THEN
		SET currentfactid = NULL;
	END IF;
    IF(currentzoneid = '' OR currentzoneid = 0) THEN
		SET currentzoneid = NULL;
	END IF;
    IF(transhareid = '' OR transhareid = 0) THEN
		SET transhareid = NULL;
	END IF;
    
	SELECT          ams.transporterid
			, ams.zoneid
            		, ams.factoryid			
			, ams.total_weight
			, ams.shared_weight
			, ams.actualpercent
			, ams.customerno
   FROM transporter_actualshare AS ams
  
   WHERE 	(ams.customerno = custno OR custno IS NULL)
   AND		(ams.transporterid = currenttransporterid OR currenttransporterid IS NULL)
   AND		(ams.zoneid = currentzoneid OR currentzoneid IS NULL)
   AND		(ams.factoryid = currentfactid OR currentfactid IS NULL)
   AND 		ams.isdeleted = 0;
END$$
DELIMITER ;			
	
    
/* Need To Be Check */    
 ALTER TABLE actual_monthly_share RENAME transporter_actualshare; 
 
 
DELIMITER $$
DROP PROCEDURE IF EXISTS `update_proposed_indent`$$
CREATE PROCEDURE `update_proposed_indent`( 
	IN propindentid int,
	IN factoryid int,
    IN depotid int,
	IN total_weight float(6,2),
	IN total_volume float(6,2),
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
	END IF ;

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

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `update_pit_mapping`$$
CREATE PROCEDURE `update_pit_mapping`( 
	IN pitmapid INT
	, IN proposedindentid INT
	, IN proposed_transporterid INT
    , IN proposed_vehtypeid INT
    , IN actual_vehtypeid INT
    , IN vehicleno varchar(20)
    , IN isAccepted tinyint(1)
	, IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE 	proposed_indent_transporter_mapping
	SET 	proposedindentid = proposedindentid
			, proposed_transporterid = proposed_transporterid
            , proposed_vehicletypeid = proposed_vehtypeid
            , actual_vehicletypeid = actual_vehtypeid
            , vehicleno = vehicleno
            , isAccepted = isAccepted
			, updated_on = todaysdate 
            , updated_by = userid
	WHERE 	pitmappingid = pitmapid;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_pit_mapping`$$
CREATE PROCEDURE `insert_pit_mapping`( 
	IN proposedindentid int,
	IN proposed_transporterid int,
    IN proposed_vehicletypeid INT,
	IN customerno INT
	, IN todaysdate DATETIME
    , IN userid INT
    , OUT currentpitmappingid INT
)
BEGIN

	INSERT INTO proposed_indent_transporter_mapping(
								proposedindentid 
								,proposed_transporterid
                                , proposed_vehicletypeid
								,customerno
								, created_on
								, updated_on
								, created_by
								, updated_by
                            ) 
	VALUES ( 
				proposedindentid
                , proposed_transporterid
		, proposed_vehicletypeid
                , customerno
                , todaysdate
                , todaysdate
                , userid
                , userid
			);
	SET currentpitmappingid = LAST_INSERT_ID();

END$$
DELIMITER ;


ALTER TABLE `proposed_indent_sku_mapping` CHANGE `proposed_indent_sku_mappingid` `proposed_indent_sku_mappingid` INT(11) NOT NULL AUTO_INCREMENT ;

/********************************************************************************************/



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
			, f.factoryname
			, pi.depotid
			, d.depotname
			,t.transportername
	       	        , vehtype.vehiclecode
		        , pit.vehicleno
		        , pi.date_required
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
DROP PROCEDURE IF EXISTS `get_proposed_indent_sku_mapping`$$
CREATE PROCEDURE `get_proposed_indent_sku_mapping`(
	IN custno INT
    , propindentid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(propindentid = '' OR propindentid = 0) THEN
		SET propindentid = NULL;
	END IF;
	SELECT 	        pism.proposedindentid
			, s.skuid
			, s.skucode
		        , s.sku_description
			, pism.no_of_units
			, pism.weight
			, pism.volume
			, pism.customerno
			, pism.created_on
			, pism.updated_on 
			, pism.created_by
			, pism.updated_by
	FROM 	proposed_indent_sku_mapping as pism
    
    
    INNER JOIN sku s ON s.skuid = pism.skuid
	WHERE 	(pism.customerno = custno OR custno IS NULL)
    AND		(pism.proposedindentid = propindentid OR propindentid IS NULL)
	AND 	pism.isdeleted = 0;
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

ALTER TABLE `proposed_indent` ADD `hasTransporterAccepted` TINYINT(1) NOT NULL default 0 AFTER `isApproved`;

/***********************************************************************/

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
	LEFT OUTER JOIN skutypes as st on st.tid = sku.skutypeid
	WHERE 	(sku.customerno = custno OR custno IS NULL)
    AND		(skuid = skuidparam OR skuidparam IS NULL)
	AND 	sku.isdeleted = 0;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_factories`$$
CREATE PROCEDURE `get_factories`(
	IN custno INT
	, IN factid INT
	, IN zid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(factid = '' OR factid = 0) THEN
		SET factid = NULL;
	END IF;
	IF(zid = '' OR zid = 0) THEN
		SET zid = NULL;
	END IF;
	SELECT factoryid
			,factorycode
			,factoryname
			,f.zoneid
			,z.zonename
			,f.customerno
			,f.created_on
			,f.updated_on
			,f.created_by
			,f.updated_by
	FROM   	factory AS f INNER JOIN 
			zone AS z ON z.zoneid = f.zoneid
	WHERE   (f.customerno = custno OR custno IS NULL)
	AND 	(f.factoryid = factid OR factid IS NULL)
    AND 	(f.zoneid = zid OR zid IS NULL)
    AND		f.isdeleted = 0;
END


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
    GROUP BY date_required,factoryid, depotid, skuid;

END
