DELIMITER $$
DROP PROCEDURE IF EXISTS `get_locations`$$
CREATE PROCEDURE `get_locations`(
	IN custno INT
    , IN locid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(locid = '' OR locid = 0) THEN
		SET locid = NULL;
	END IF;
	SELECT locationid
			, locationname
			, customerno
			, created_on
			, updated_on
   FROM location
   WHERE (customerno = custno OR custno IS NULL)
   AND	(locationid = locid OR locid IS NULL)
   AND isdeleted = 0;
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_factory`$$
CREATE PROCEDURE `insert_factory`( 
	IN factorycode VARCHAR (10)	
	,IN factoryname VARCHAR (50)
    ,IN locationid INT
	,IN customerno INT
    ,IN todaysdate DATETIME
    ,IN userid INT
    , OUT currentfactoryid INT
	)
BEGIN
	INSERT INTO factory (
							factorycode							
							,factoryname
							, locationid
                            				, customerno
							, created_on
							, updated_on
							, created_by
							, updated_by
						)
	VALUES ( 
				factorycode				
				,factoryname
                		, locationid
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	SET currentfactoryid = LAST_INSERT_ID();

END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `get_factories`$$
CREATE  PROCEDURE `get_factories`(
	IN custno INT
	, IN factid INT
	, IN locid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(factid = '' OR factid = 0) THEN
		SET factid = NULL;
	END IF;
	IF(locid = '' OR locid = 0) THEN
		SET locid = NULL;
	END IF;
	SELECT factoryid
			,factorycode
			,factoryname
			,f.locationid
			,l.locationname
			,f.customerno
			,f.created_on
			,f.updated_on
			,l.locationname
			,f.created_by
			,f.updated_by
	FROM   	factory AS f INNER JOIN 
			location AS l ON l.locationid = f.locationid
	WHERE   (f.customerno = custno OR custno IS NULL)
	AND 	(f.factoryid = factid OR factid IS NULL)
    AND 	(f.locationid = locid OR locid IS NULL)
    AND		f.isdeleted = 0;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `update_factory`$$
CREATE  PROCEDURE `update_factory`( 
	  IN factorycode VARCHAR (10)	  
,IN factoryname VARCHAR (50)
    , IN locationid INT
	, IN fid INT
	, IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE factory 
    SET  factorycode = factorycode
,factoryname = factoryname
		, locationid = locationid
        , updated_on = todaysdate
        , updated_by = userid
	WHERE factoryid = fid;
END$$
DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_transportershare`$$
CREATE PROCEDURE `insert_transportershare`( 
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
            
	SET currenttransportershareid = LAST_INSERT_ID();

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `update_transportershare`$$
CREATE PROCEDURE `update_transportershare`(
	IN transhareid INT
    , IN transporterid INT
, IN factoryid INT
	, IN zoneid INT
    , IN sharepercent decimal(6, 2)
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE transportershare
    SET  transporterid = transporterid
		, factoryid = factoryid
		, zoneid = zoneid
        , sharepercent = sharepercent
		, updated_on = todaysdate
        , updated_by = userid
	WHERE transportershareid = transhareid;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_transportershare`$$
CREATE  PROCEDURE `get_transportershare`(
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
    
	SELECT          t.transporterid
			, t. transportername
			, ts.sharepercent
			, ts.transportershareid
			, z.zoneid
            , z.zonename
			, f.factoryid			
			, f.factoryname
			, ts.customerno
   FROM transportershare AS ts
   INNER JOIN transporter AS t ON t.transporterid = ts.transporterid
   INNER JOIN zone AS z ON z.zoneid = ts.zoneid
   INNER JOIN factory AS f ON ts.factoryid = f.factoryid
   WHERE 	(ts.customerno = custno OR custno IS NULL)
   AND		(ts.transporterid = currenttransporterid OR currenttransporterid IS NULL)
   AND		(ts.zoneid = currentzoneid OR currentzoneid IS NULL)
   AND		(ts.transportershareid = transhareid OR transhareid IS NULL)
AND		(ts.factoryid = currentfactid OR currentfactid IS NULL)
   AND 		ts.isdeleted = 0;
END$$
DELIMITER ;			


/* ***************************************************************************************************/


DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_routemaster`$$
CREATE  PROCEDURE `insert_routemaster`( 
	IN routename VARCHAR(20)
	,IN routedescription VARCHAR(50)
	, IN fromlocationid INT
    , IN tolocationid INT
    , IN distance INT
    , IN travellingtime INT
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentroutemasterid INT
	)
BEGIN
	INSERT INTO routemaster(
							routename
				, routedescription                            
				, fromlocationid
                            , tolocationid
                            , distance
                            , travellingtime
                            , customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						)
	VALUES ( 
				routename
				, routedescription				
				, fromlocationid
				, tolocationid
				, distance
                , travellingtime
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	SET currentroutemasterid = LAST_INSERT_ID();

END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `get_routemaster`$$
CREATE  PROCEDURE `get_routemaster`(
	IN custno INT
    , IN rtmasterid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(rtmasterid = '' OR rtmasterid = 0) THEN
		SET rtmasterid = NULL;
	END IF;
	SELECT 	r.routemasterid
			, r.routename
			, r.routedescription
			, r.fromlocationid
            , fact.factoryname
			, r.tolocationid
            , d.depotname
            , r.distance
            , r.travellingtime
			, r.customerno
   FROM routemaster AS r
   INNER JOIN factory AS fact ON fact.factoryid = r.fromlocationid
   INNER JOIN depot AS d ON d.depotid = r.tolocationid
   WHERE 	(r.customerno = custno OR custno IS NULL)
   AND 		(r.routemasterid = rtmasterid OR rtmasterid IS NULL)
   AND 		r.isdeleted = 0;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_routecheckpoint`$$
CREATE  PROCEDURE `insert_routecheckpoint`( 
	IN routemasterid INT	  
	,IN fromlocationid INT
    , IN tolocationid INT
    , IN distance INT
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentroutecheckpointid INT
	)
BEGIN
	INSERT INTO routecheckpoint(
							routemasterid
                            , fromlocationid
                            , tolocationid
                            , distance
                            , customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						)
	VALUES ( 
				routemasterid
				, fromlocationid
				, tolocationid
				, distance
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	SET currentroutecheckpointid = LAST_INSERT_ID();

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_routecheckpoints`$$
CREATE PROCEDURE `get_routecheckpoints`(
	IN custno INT
    , IN routechkptid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF routechkptid = '' OR routechkptid = 0 THEN
		SET routechkptid = NULL;
    END IF;
	SELECT 	 r.routecheckpointid
			,rm.routename				
			,r.routemasterid
			,r.fromlocationid
            , fact.factoryname
			, r.tolocationid
            , toloc.locationname
            , r.distance
			, r.customerno
   FROM routecheckpoint AS r
   INNER JOIN factory AS fact ON fact.factoryid = r.fromlocationid
   INNER JOIN location AS toloc ON toloc.locationid = r.tolocationid
   INNER JOIN routemaster AS rm ON rm.routemasterid = r.routemasterid
   WHERE 	(r.customerno = custno OR custno IS NULL)
   AND		(r.routecheckpointid = routechkptid OR routechkptid IS NULL)
   AND 		r.isdeleted = 0;
END$$
DELIMITER ;






DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_sku`$$
CREATE  PROCEDURE `insert_sku`( 
	IN skucode varchar(100)
	, IN sku_description varchar(250)
	, IN type varchar(25)
	, IN volume float (6,2)
	, IN weight float (6,2)
	, IN customerno INT
	, IN todaysdate DATETIME
    , IN userid INT
    , OUT currentskuid INT
)
BEGIN
	INSERT INTO sku 
				(
					skucode
					, sku_description
					, skutypeid
					, volume
					, weight
					, customerno
					, created_on
					, updated_on
                    , created_by
                    , updated_by
				) 
	VALUES 		(
					skucode
					, sku_description
					, type
					, volume
					, weight
					, customerno
					, todaysdate
					, todaysdate
                    , userid
                    , userid
				);

        
	SET currentskuid = LAST_INSERT_ID();
END$$
DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `get_factory_delivery`$$
CREATE PROCEDURE `get_factory_delivery`(
	IN custno INT
    , IN fdidparam INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(fdidparam = '' OR fdidparam = 0) THEN
		SET fdidparam = NULL;
	END IF;
	SELECT 	f.factoryid
			, fd.fdid
			, f.factoryname
            , s.skuid
            , s.skucode
            , s.sku_description
            , d.depotid
            , d.depotname
            , date_required
            , fd.weight
            , fd.customerno
	FROM 	factory_delivery fd
    INNER JOIN factory f ON f.factoryid = fd.factoryid
    INNER JOIN sku s ON s.skuid = fd.skuid
    INNER JOIN depot d ON d.depotid = fd.depotid
	WHERE 	(fd.customerno = custno OR custno IS NULL)
    AND 	(fdid = fdidparam OR fdidparam IS NULL)
	AND 	fd.isdeleted = 0;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_factory_production`$$
CREATE PROCEDURE `get_factory_production`(
	IN custno INT
    , IN fpidparam INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(fpidparam = '' OR fpidparam = 0) THEN
		SET fpidparam = NULL;
	END IF;
	SELECT 	f.factoryid
		,fp.fpid
			, f.factoryname
            , s.skuid
            , s.skucode
            , s.sku_description
            
            , fp.weight
            , fp.customerno
	FROM 	factory_production fp
    INNER JOIN factory f ON f.factoryid = fp.factoryid
    INNER JOIN sku s ON s.skuid = fp.skuid
	WHERE 	(fp.customerno = custno OR custno IS NULL)
    AND 	(fp.fpid = fpidparam OR fpidparam IS NULL)
	AND 	fp.isdeleted = 0;
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_zone`$$
CREATE PROCEDURE `insert_zone`( 
	IN zonename VARCHAR (25)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT 
    , OUT currentzoneid INT
	)
BEGIN

	INSERT INTO zone(
						zonename
						, customerno
						, created_on
						, updated_on
                        , created_by
                        , updated_by
					)
	VALUES ( 
				zonename
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	SET currentzoneid = LAST_INSERT_ID();

END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `update_zone`$$
CREATE  PROCEDURE `update_zone`( 
	IN zid INT
        ,IN zonename VARCHAR (25)
	
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	/* DECLARE noOfRowsAffected INT; 
    START TRANSACTION;*/
	UPDATE zone 
	SET  zonename = zonename
		, updated_on = todaysdate
		, updated_by = userid
	WHERE zoneid = zid;
        /*
	SET noOfRowsAffected = ROW_COUNT();

	IF(noOfRowsAffected = 1) THEN COMMIT;
		ELSE ROLLBACK;
	END IF;    
        */
END$$

DELIMITER ;







/**************************************************************************/


DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_depot`$$
CREATE PROCEDURE `insert_depot`( 
	IN depotcode VARCHAR (20)
	, IN depotname VARCHAR (50)
    , IN zoneid INT
    , IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentdepotid INT
	)
BEGIN
	INSERT INTO depot(
							depotcode
                            , depotname
                            , zoneid
						
                            , customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						)
	VALUES ( 
				depotcode
                , depotname
                , zoneid
               		, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	SET currentdepotid = LAST_INSERT_ID();

END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `get_depots`$$
CREATE PROCEDURE `get_depots`(
	IN custno INT
    , IN zid INT
    , IN did INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    
    IF(zid = '' OR zid = 0) THEN
		SET zid = NULL;
	END IF;
	IF(did = '' OR did = 0) THEN
		SET did = NULL;
	END IF;
	SELECT 	depotid
			, depotcode
            , depotname
            , d.zoneid
			
			, d.customerno
			, d.created_on
			, d.updated_on
			, z.zonename
			
   FROM depot as d
   INNER JOIN zone as z on z.zoneid = d.zoneid
   WHERE (d.customerno = custno OR custno IS NULL)
   AND 	(d.zoneid = zid OR zid IS NULL)
   AND 	(d.depotid = did OR did IS NULL)
   AND 	d.isdeleted = 0;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `update_depot`$$
CREATE PROCEDURE `update_depot`( 
	IN depotcode VARCHAR(20)
	, IN depotname VARCHAR (50)
	, IN zoneid INT
    	, IN did INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE depot
    SET  depotcode = depotcode
		, depotname = depotname
        , zoneid = zoneid
		
        , updated_on = todaysdate
        , updated_by = userid
	WHERE depotid = did;
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_factory`$$
CREATE  PROCEDURE `insert_factory`( 
	IN factorycode VARCHAR (10)	
	,IN factoryname VARCHAR (50)
    ,IN zoneid INT
	,IN customerno INT
    ,IN todaysdate DATETIME
    ,IN userid INT
    , OUT currentfactoryid INT
	)
BEGIN
	INSERT INTO factory (
							factorycode							
							,factoryname
							, zoneid
                            				, customerno
							, created_on
							, updated_on
							, created_by
							, updated_by
						)
	VALUES ( 
				factorycode
				,factoryname                		
				, zoneid
				, customerno
				, todaysdate
				, todaysdate
                		, userid
                		, userid
			);
            
	SET currentfactoryid = LAST_INSERT_ID();

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `update_factory`$$
CREATE PROCEDURE `update_factory`( 
	  IN factorycode VARCHAR (10)
	,IN factoryname VARCHAR (50)
    , IN zoneid INT
	, IN fid INT
	, IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE factory 
    SET  factorycode = factorycode
	,factoryname = factoryname	
	, zoneid = zoneid
        , updated_on = todaysdate
        , updated_by = userid
	WHERE factoryid = fid;
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
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `update_routemaster`$$
CREATE PROCEDURE `update_routemaster`(
	IN rtmasterid INT
    , IN routename VARCHAR(20)
	, IN routedescription VARCHAR(20)
	, IN fromlocationid INT
    , IN tolocationid INT
    , IN distance INT
    , IN travellingtime INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE routemaster
    SET  routename = routename
		,routedescription = routedescription
		, fromlocationid = fromlocationid
        , tolocationid = tolocationid
        , distance = distance
        , travellingtime = travellingtime
		, updated_on = todaysdate
        , updated_by = userid
	WHERE routemasterid = rtmasterid;
END$$
DELIMITER ;





DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_sku`$$
CREATE  PROCEDURE `delete_sku`( 
	IN skuidparam INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE 	sku
	SET  	isdeleted = 1
			, updated_on  = todaysdate
            , updated_by = userid
	WHERE	skuid = skuidparam;

END$$
DELIMITER ;

create table skutypes(
`tid` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
`type` varchar(50) NOT NULL,
`customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'
);


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
	INNER JOIN skutypes as st on st.tid = sku.skutypeid
	WHERE 	(sku.customerno = custno OR custno IS NULL)
    AND		(skuid = skuidparam OR skuidparam IS NULL)
	AND 	sku.isdeleted = 0;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `update_sku`$$
CREATE PROCEDURE `update_sku`( 
	IN skuidparam INT
	, IN skucode varchar(100)
	, IN sku_description varchar(250)
	, IN typeid varchar(25)
	, IN volume float (6,2)
	, IN weight float (6,2)
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE sku
	SET 
		skucode = skucode
		, sku_description = sku_description
		, skutypeid = typeid
		, volume = volume
		, weight = weight
		, updated_on = todaysdate
        , updated_by = userid
	WHERE skuid = skuidparam;
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_transporter`$$
CREATE PROCEDURE `insert_transporter`( 
	IN transportercode VARCHAR (20)
	, IN transportername VARCHAR (50)
    , IN transportermail VARCHAR (150)
    , IN transportermobileno VARCHAR (50)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currenttransporterid INT
	)
BEGIN
	INSERT INTO transporter(
							transportercode
                            , transportername
                            , transportermail
							, transportermobileno
                            , customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						)
	VALUES ( 
				transportercode
                , transportername
                , transportermail
				, transportermobileno
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	SET currenttransporterid = LAST_INSERT_ID();

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `update_transporter`$$
CREATE  PROCEDURE `update_transporter`( 
	IN transportercode VARCHAR(20)
	, IN transportername VARCHAR (50)
    , IN transportermail VARCHAR (150)
    , IN transportermobileno VARCHAR (50)
	, IN tranid INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE transporter
    SET  transportercode = transportercode
		, transportername = transportername
        , transportermail = transportermail
		, transportermobileno = transportermobileno
        , updated_on = todaysdate
		, updated_by = userid
	WHERE transporterid = tranid;
END$$
DELIMITER ;



/**************************************************************************************************************/




DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_vehicletype`$$
CREATE PROCEDURE `insert_vehicletype`( 
	IN vehiclecode VARCHAR (20)
	, IN vehicledescription VARCHAR (50)
, IN tid INT (11)
    , IN volume VARCHAR (50)
    , IN weight VARCHAR (15)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentvehicletypeid INT
	)
BEGIN
	INSERT INTO vehicletype(
							vehiclecode
                            , vehicledescription
		 	    , skutypeid
                            , volume
							, weight
                            , customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						)
	VALUES ( 
				vehiclecode
                , vehicledescription
		, tid
                , volume
				, weight
				, customerno
				, todaysdate
				, todaysdate
                , userid
                , userid
			);
            
	SET currentvehicletypeid = LAST_INSERT_ID();

END$$
DELIMITER ;

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
DROP PROCEDURE IF EXISTS `get_vehicletypes`$$
CREATE PROCEDURE `get_vehicletypes`(
	IN custno INT
	,IN vehtypeid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
        IF(vehtypeid = '' OR vehtypeid = 0) THEN
		SET vehtypeid = NULL;
	END IF;
	SELECT 	vehicletypeid
			, vehiclecode
            , vehicledescription
	    , v.skutypeid
	    , st.type
            , volume
			, weight
			, v.customerno
			, v.created_on
			, v.updated_on
   FROM vehicletype as v
   INNER JOIN skutypes as st ON st.tid = v.skutypeid
   WHERE (v.customerno = custno OR custno IS NULL)
   AND (v.vehicletypeid = vehtypeid OR vehtypeid IS NULL)
   AND	v.isdeleted = 0;
END$$
DELIMITER ;


/************************************************************************************/



DELIMITER $$
DROP PROCEDURE IF EXISTS `get_skuweight`$$
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
        GROUP BY date_required, factoryid, depotid;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_vehicletypes`$$
CREATE PROCEDURE `get_vehicletypes`(
	IN custno INT
	,IN vehtypeid INT
	,IN sku_typeid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
        IF(vehtypeid = '' OR vehtypeid = 0) THEN
		SET vehtypeid = NULL;
	END IF;
IF(sku_typeid = '' OR sku_typeid = 0) THEN
		SET sku_typeid = NULL;
	END IF;
	SELECT 	vehicletypeid
			, vehiclecode
            , vehicledescription
	    , v.skutypeid
	    , st.type
            , volume
			, weight
			, v.customerno
			, v.created_on
			, v.updated_on
   FROM vehicletype as v
   INNER JOIN skutypes as st ON st.tid = v.skutypeid
   WHERE (v.customerno = custno OR custno IS NULL)
   AND (v.vehicletypeid = vehtypeid OR vehtypeid IS NULL)
   AND (v.skutypeid = sku_typeid OR sku_typeid IS NULL)
   AND	v.isdeleted = 0 order by ispreferred DESC;
END$$
DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `get_vehtypetransporter_mapping`$$
CREATE PROCEDURE `get_vehtypetransporter_mapping`(
	IN custno INT
	,IN transid INT
)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
	IF(transid = '' OR transid = 0) THEN
		SET transid = NULL;
	END IF;
       
	SELECT 	vtm.vehicletypeid
            ,vtm.vehiclecount
            ,vt.vehiclecode
            ,vt.volume
            ,vt.weight
            ,vtm.transporterid
            FROM vehtypetransmapping vtm
            INNER JOIN vehicletype vt ON vtm.vehicletypeid = vt.vehicletypeid
    	WHERE 	(vtm.customerno = custno OR custno IS NULL)
    	AND (vtm.transporterid = transid OR transid IS NULL) order by vt.weight DESC;

END$$
DELIMITER ;




create table actual_monthy_share(
`actshareid` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
`factoryid` int(11)NOT NULL,
`depotid` int(11) NOT NULL,
`transporterid` int(11) NOT NULL,
`shared_weight` decimal(11,3) NOT NULL,
`total_weight` decimal(11,3) NOT NULL,
`actualpercent` decimal(6,2)NOT NULL,
`customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'
);


CREATE TABLE IF NOT EXISTS `proposed_indent_sku_mapping` (
  `indent_sku_mappingid` int(11) NOT NULL,
  `proposedindentid` int(11) NOT NULL,
  `skuid` int(11) NOT NULL,
  `no_of_units` int(11) NOT NULL,
  `weight` decimal(11,3) NOT NULL,
  `volume` decimal(11,3) NOT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'
);





ALTER table factory add factorycode varchar(10)NOT NULL AFTER factoryid;
ALTER TABLE transportershare add factoryid INT(11) NOT NULL AFTER transporterid;
ALTER TABLE routemaster add routedescription varchar(50) NOT NULL AFTER routename;
Alter Table sku add type Varchar(25) NOT NULL AFTER sku_description;
ALTER TABLE `zone` CHANGE `zonename` `zonename` VARCHAR(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `factory_delivery` ADD `isProcessed` TINYINT(1) NOT NULL DEFAULT 0 AFTER `weight`;
ALTER TABLE `factory` CHANGE `locationid` `zoneid` INT(11) NULL DEFAULT NULL;
ALTER TABLE `sku` CHANGE `weight` `weight` DECIMAL(9,5) NULL DEFAULT NULL;
ALTER TABLE `transporter` CHANGE `transportermail` `transportermail` VARCHAR(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, ALTER TABLE `transporter` CHANGE `transportermobileno` `transportermobileno` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `transportershare` CHANGE `sharepercent` `sharepercent` DECIMAL(5,2) NOT NULL;
ALTER TABLE `vehicletype` CHANGE `typeid` `skutypeid` INT(11) NOT NULL;
ALTER TABLE vehicletype add ispreferred tinyint(1) NOT NULL AFTER weight;
ALTER TABLE `sku` CHANGE `type` `skutypeid` INT(11) NOT NULL;
ALTER TABLE proposed_indent ADD depotid INT(11) NOT NULL AFTER  factoryid;
ALTER TABLE `vehicletype` CHANGE `volume` `volume` DECIMAL(11,3) NOT NULL;
ALTER TABLE `vehicletype` CHANGE `weight` `weight` DECIMAL(11,3) NOT NULL;
ALTER TABLE `proposed_indent_transporter_mapping` CHANGE `vehicleid` `vehicleno` VARCHAR(20) NULL DEFAULT NULL;
ALTER TABLE `vehicletype` CHANGE `ispreferred` `ispreferred` TINYINT( 1 ) NOT NULL 



