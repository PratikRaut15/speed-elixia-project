ALTER TABLE `sku` add netgross decimal(5,2) AFTER weight;
ALTER TABLE `sku` ADD CONSTRAINT ux_skucodedescription UNIQUE (`skucode`,`sku_description`);
ALTER TABLE `proposed_indent_transporter_mapping` ADD drivermobileno varchar(12) AFTER vehicleno;

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_sku`$$
CREATE PROCEDURE `insert_sku`( 
	IN skucode varchar(100)
	, IN sku_description varchar(250)
	, IN type varchar(25)
	, IN volume decimal (9,5)
	, IN weight decimal (9,5)
	, IN netgross decimal(5,2)
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
					, netgross
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
					, netgross
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
			, netgross
			, sku.customerno
	FROM 	sku
	left JOIN skutypes as st on st.tid = sku.skutypeid
	WHERE 	(sku.customerno = custno OR custno IS NULL)
    AND		(skuid = skuidparam OR skuidparam IS NULL)
	AND 	sku.isdeleted = 0;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `update_sku`$$
CREATE  PROCEDURE `update_sku`( 
	IN skuidparam INT
	, IN skucode varchar(100)
	, IN sku_description varchar(250)
	, IN typeid varchar(25)
	, IN volume decimal (9,5)
	, IN weight decimal (9,5)
	, IN netgross decimal (5,2)
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
		, netgross = netgross
		, updated_on = todaysdate
        , updated_by = userid
	WHERE skuid = skuidparam;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `update_pit_mapping`$$
CREATE  PROCEDURE `update_pit_mapping`( 
	IN pitmapid INT
	, IN proposedindentid INT
	, IN proposed_transporterid INT
    , IN proposed_vehtypeid INT
    , IN actual_vehtypeid INT
    , IN vehicleno varchar(20)
    , IN drivermobileno varchar(12)
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
	    , drivermobileno = drivermobileno
            , isAccepted = isAccepted
			, updated_on = todaysdate 
            , updated_by = userid
	WHERE 	pitmappingid = pitmapid;

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
			, pit.drivermobileno
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
DROP PROCEDURE `insert_transporteractualshare`$$
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
DROP PROCEDURE IF EXISTS `insert_vehtypetransporter_mapping`$$
CREATE PROCEDURE `insert_vehtypetransporter_mapping`( 
	IN transporterid INT
	, IN vehtypeid INT
	, IN customerno INT
	, IN todaysdate DATETIME
    , IN userid INT

)
BEGIN
	INSERT INTO vehtypetransmapping 
				(
					transporterid
					, vehicletypeid
					, customerno
					, created_on
					, updated_on
                    , created_by
                    , updated_by
				) 
	VALUES 		(
					transporterid
					, vehtypeid
					, customerno
					, todaysdate
					, todaysdate
                    , userid
                    , userid
				);

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_transporter`$$
CREATE  PROCEDURE `delete_transporter`(
	IN tranid INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE transporter 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE transporterid = tranid;
    
	CALL delete_transportershare(NULL, tranid, todaysdate, userid);    
	CALL delete_vehtypetransporter_mapping(NULL, tranid, todaysdate, userid);
    
    
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_vehtypetransporter_mapping`$$
CREATE  PROCEDURE `delete_vehtypetransporter_mapping`(
	IN vtmidparam INT	
	,IN transid INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	IF(vtmidparam = '' OR vtmidparam = 0) THEN
		SET vtmidparam = NULL;
	END IF;
	UPDATE vehtypetransmapping 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE transporterid = transid
	AND (vtmid = vtmidparam OR vtmidparam IS NULL);
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_vehtypetransporter_mapping`$$
CREATE  PROCEDURE `get_vehtypetransporter_mapping`(
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
	    ,vt.vehicledescription
            ,vt.volume
            ,vt.weight
            ,vtm.transporterid
            FROM vehtypetransmapping vtm
            INNER JOIN vehicletype vt ON vtm.vehicletypeid = vt.vehicletypeid
    	WHERE 	(vtm.customerno = custno OR custno IS NULL)
    	AND (vtm.transporterid = transid OR transid IS NULL) AND vtm.isdeleted=0 order by vt.weight DESC;

END$$
DELIMITER ;



