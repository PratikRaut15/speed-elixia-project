create index index_customerno on multidepot_mapping(customerno);
create index index_factoryid on multidepot_mapping(factoryid);

create index index_customerno on leftoverdetails(customerno);
create index index_factoryid on leftoverdetails(factoryid);

delete from factory_delivery where depotid = 0;

ALTER TABLE `vehicletype` DROP INDEX ux_vehiclecode;
ALTER TABLE `vehicletype` ADD CONSTRAINT ux_vehiclecode UNIQUE (`vehiclecode`, `isdeleted`); 

Truncate Table factory_delivery;

create index index_customerno on factory_delivery(customerno);
create index index_factoryid on factory_delivery(factoryid);
create index index_depotid on factory_delivery(depotid);

CREATE TABLE `factory_delivery_history` (
`fdhid` int(11) NOT NULL AUTO_INCREMENT,
  `fdid` int(11) NOT NULL,
  `factoryid` int(11) NOT NULL,
  `skuid` int(11) DEFAULT NULL,
  `depotid` int(11) DEFAULT NULL,
  `date_required` date DEFAULT NULL,
  `netWeight` decimal(7,3) NOT NULL,
  `grossWeight` decimal(7,3) NOT NULL,
  `isProcessed` tinyint(1) NOT NULL DEFAULT '0',
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fdhid`)
);



DELIMITER $$
DROP PROCEDURE IF EXISTS get_multidepot_load$$ 
CREATE PROCEDURE get_multidepot_load( 
	 IN factoryidparam INT
	, IN multidepotcombination VARCHAR(50)
    , IN custno INT
    , IN reqddate DATETIME
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
    DECLARE multidropdepotid INT DEFAULT 0;
    
    SELECT 	depotid
    INTO 	multidropdepotid
	FROM	multidepot_mapping
	WHERE	factoryid = factoryidparam
    AND		LTRIM(RTRIM(depotmappingid)) = LTRIM(RTRIM(multidepotcombination))
    AND		customerno = custno
    AND     isdeleted = 0; 
    IF (multidropdepotid IS NOT NULL AND multidropdepotid != 0) THEN
		BEGIN
			INSERT INTO factory_delivery( 
										factoryid
										, skuid
										, depotid
										, date_required
										, netWeight
										, grossWeight
										, customerno
										, created_on
										, updated_on 
										, created_by
										, updated_by
									)
			SELECT 		factoryid
						, sku.skuid
                        , multidropdepotid
						, daterequired
						, (sum(totalWeight) / (netgross))
                        , sum(totalWeight)
                        , lod.customerno
                        , todaysdate
                        , todaysdate
                        , userid
                        , userid
            FROM		leftoverdetails AS lod
            INNER JOIN 	leftover_sku_mapping AS lsm ON lod.leftoverid = lsm.leftoverid
            INNER JOIN	sku ON lsm.skuid = sku.skuid
            WHERE		factoryid = factoryidparam
            AND 		FIND_IN_SET(lod.depotid, multidepotcombination)
            AND			isProcessed = 0
            AND			lod.customerno = custno
            GROUP BY 	daterequired, factoryid, depotid;
            
            /* UPDATE the above entries as processed in SKU left over details*/
            
            UPDATE	leftoverdetails AS lod
            SET 	isProcessed = 1
					, updated_on = todaysdate
                    , updated_by = userid
			WHERE	factoryid = factoryidparam
            AND 	FIND_IN_SET(lod.depotid, multidepotcombination)
            AND		daterequired = reqddate
			AND		customerno = custno;
            
		END;
	END IF;
    
    
END$$
DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_factory_delivery_history`$$
CREATE PROCEDURE `insert_factory_delivery_history`(
        IN custno INT
        , IN isProcessedparam INT
        , IN daterequiredparam DATE
)
BEGIN

DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		ROLLBACK;
	END;
    
    START TRANSACTION;
    
    IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    IF(isProcessedparam = '' OR isProcessedparam = 0) THEN
		SET isProcessedparam = NULL;
    END IF;
    IF(daterequiredparam = '' OR daterequiredparam = 0) THEN
		SET daterequiredparam = NULL;
    END IF;

    INSERT INTO factory_delivery_history(
    fdid,
    factoryid,
    skuid,
    depotid,
    date_required,
    netWeight,
    grossWeight,
    isProcessed,
    customerno,
    created_on,
    updated_on,
    created_by,
    updated_by,
    isdeleted
    )
     SELECT 	
            fd.fdid
            , fd.factoryid
            , fd.skuid
            , fd.depotid
            , fd.date_required
            , fd.netWeight
            , fd.grossWeight
            , fd.isProcessed
            , fd.customerno
            , fd.created_on
            , fd.updated_on
			, fd.created_by
            , fd.updated_by
            , fd.isdeleted
	FROM    factory_delivery fd
    WHERE (fd.customerno = custno OR custno IS NULL)
    AND   (fd.isProcessed = isProcessedparam OR isProcessedparam IS NULL)
    AND ((fd.date_required < daterequiredparam) OR daterequiredparam IS NULL)
    AND   fd.isdeleted = 0;

	call delete_processed_factory_delivery(custno,daterequiredparam);

	COMMIT;
    
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_processed_factory_delivery`$$
CREATE PROCEDURE `delete_processed_factory_delivery`(
        IN custno INT
        , IN daterequiredparam DATE
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    IF(daterequiredparam = '' OR daterequiredparam = 0) THEN
		SET daterequiredparam = NULL;
    END IF;

    DELETE FROM factory_delivery 
    WHERE (customerno = custno OR custno IS NULL)
    AND ((date_required < daterequiredparam) OR daterequiredparam IS NULL)
    AND   isProcessed = 1
    AND   isdeleted = 0;
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `get_left_over_details`$$
CREATE PROCEDURE `get_left_over_details`(
        IN custno INT
        ,IN factoryidparam INT
        ,IN dateparam DATE
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
	SET custno = NULL;
    END IF;
    IF(factoryidparam = '' OR factoryidparam = 0) THEN
	SET factoryidparam = NULL;
    END IF;
    IF(dateparam = '' OR dateparam = 0) THEN
		SET dateparam = NULL;
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
    AND ((ld.daterequired = dateparam) OR dateparam IS NULL)
    AND ld.isProcessed = 0
    AND   ld.isdeleted = 0;
END$$
DELIMITER ;




-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 11, NOW(), 'Shrikant Suryawanshi','Insert into factory delivery history and ');
