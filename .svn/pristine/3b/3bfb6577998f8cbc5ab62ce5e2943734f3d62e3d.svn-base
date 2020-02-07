DELIMITER $$
DROP PROCEDURE IF EXISTS `get_factory_delivery`$$
CREATE PROCEDURE `get_factory_delivery`(
        IN custno INT
        , IN fdidparam INT
        , IN factoryidparam INT
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
    FROM    factory_delivery fd
    INNER JOIN factory f ON f.factoryid = fd.factoryid
    INNER JOIN sku s ON s.skuid = fd.skuid
    INNER JOIN depot d ON d.depotid = fd.depotid
    WHERE (fd.customerno = custno OR custno IS NULL)
    AND   (fdid = fdidparam OR fdidparam IS NULL)
    AND   (fd.factoryid = factoryidparam OR factoryidparam IS NULL)
    AND   fd.isdeleted = 0;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_proposed_indent`$$
CREATE PROCEDURE `get_proposed_indent`(
	IN custno INT
    , propindentid INT
    , factoryidparam INT
    , daterequired varchar(15)
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
        AND	(pi.proposedindentid = propindentid OR propindentid IS NULL)
        AND	(pi.factoryid = factoryidparam OR factoryidparam IS NULL)
	AND	(pi.date_required = daterequired OR daterequired IS NULL)
	AND 	pi.isdeleted = 0;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_left_over_details`$$
CREATE PROCEDURE `get_left_over_details`(
        IN custno INT
        ,IN factoryidparam INT
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
	SET custno = NULL;
    END IF;
    IF(factoryidparam = '' OR factoryidparam = 0) THEN
	SET factoryidparam = NULL;
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
    AND   ld.isdeleted = 0;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_left_over_sku`$$
CREATE PROCEDURE `get_left_over_sku`(
        IN custno INT
        ,IN leftoveridparam INT
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
	SET custno = NULL;
    END IF;
    IF(leftoveridparam = '' OR leftoveridparam = 0) THEN
	SET leftoveridparam = NULL;
    END IF;
    
    SELECT 	
            lsm.leftover_sku_mappingid
            , lsm.leftoverid
            , lsm.skuid
            , lsm.no_of_units
            , lsm.totalWeight
            , lsm.totalVolume
            , sku.skucode
            , sku.sku_description
            , lsm.customerno
    FROM    leftover_sku_mapping lsm
    INNER JOIN sku ON sku.skuid = lsm.skuid
    WHERE (lsm.customerno = custno OR custno IS NULL)
    AND (lsm.leftoverid = leftoveridparam OR leftoveridparam IS NULL)
    AND   lsm.isdeleted = 0;
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
DROP PROCEDURE IF EXISTS `get_proposed_indent`$$
CREATE PROCEDURE `get_proposed_indent`(
	IN custno INT
    , propindentid INT
    , factoryidparam INT
    , daterequired varchar(15)
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
        AND	(pi.proposedindentid = propindentid OR propindentid IS NULL)
        AND	(pi.factoryid = factoryidparam OR factoryidparam IS NULL)
	AND	(pi.date_required = daterequired OR daterequired IS NULL)
	AND 	pi.isdeleted = 0;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_left_over_details`$$
CREATE PROCEDURE `get_left_over_details`(
        IN custno INT
        ,IN factoryidparam INT
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
	SET custno = NULL;
    END IF;
    IF(factoryidparam = '' OR factoryidparam = 0) THEN
	SET factoryidparam = NULL;
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
    AND   ld.isdeleted = 0;
END$$
DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `get_left_over_sku`$$
CREATE PROCEDURE `get_left_over_sku`(
        IN custno INT
        ,IN leftoveridparam INT
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
	SET custno = NULL;
    END IF;
    IF(leftoveridparam = '' OR leftoveridparam = 0) THEN
	SET leftoveridparam = NULL;
    END IF;
    
    SELECT 	
            lsm.leftover_sku_mappingid
            , lsm.leftoverid
            , lsm.skuid
            , lsm.no_of_units
            , lsm.totalWeight
            , lsm.totalVolume
            , sku.skucode
            , sku.sku_description
            , lsm.customerno
    FROM    leftover_sku_mapping lsm
    INNER JOIN sku ON sku.skuid = lsm.skuid
    WHERE (lsm.customerno = custno OR custno IS NULL)
    AND (lsm.leftoverid = leftoveridparam OR leftoveridparam IS NULL)
    AND   lsm.isdeleted = 0;
END$$
DELIMITER ;







