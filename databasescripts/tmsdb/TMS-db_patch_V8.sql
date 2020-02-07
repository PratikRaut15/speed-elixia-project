DROP TABLE IF EXISTS `leftoverdetails`;
CREATE TABLE `leftoverdetails` (
  `leftoverid` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `factoryid` INT NOT NULL COMMENT '',
  `depotid` INT NOT NULL COMMENT '',
  `weight` DECIMAL(6,2) NOT NULL COMMENT '',
  `volume` DECIMAL(6,2) NOT NULL COMMENT '',
  `daterequired` datetime NOT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`leftoverid`)  COMMENT '');
   
  DROP TABLE IF EXISTS `leftover_sku_mapping`;
  CREATE TABLE `leftover_sku_mapping` (
  `leftover_sku_mappingid` int(11) NOT NULL AUTO_INCREMENT,
  `leftoverid` int(11) NOT NULL,
  `skuid` int(11) NOT NULL,
  `no_of_units` int(11) NOT NULL,
  `totalWeight` decimal(6,2) NOT NULL,
  `totalVolume` decimal(6,2) NOT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`leftover_sku_mappingid`)
);
  
DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_leftoverdetails`$$
CREATE PROCEDURE `insert_leftoverdetails`( 
     IN factoryid int
    , IN depotid int
    , IN weight float(6,2)
    , IN volume float(6,2)
    , IN daterequired DATETIME
    , IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentleftoverid INT
)
BEGIN
  
    INSERT INTO leftoverdetails ( 
                            factoryid
                            , depotid
                            , weight
                            , volume
                            , daterequired
                            , customerno
                            , created_on
                            , updated_on
                            , created_by
                            , updated_by
                        ) 
    VALUES  ( 
                factoryid
                , depotid
                , weight
                , volume
                , daterequired
                , customerno
                , todaysdate
                , todaysdate
                , userid
                , userid
            );
  
    SET currentleftoverid = LAST_INSERT_ID();
END$$
DELIMITER ;
  
DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_leftover_sku_mapping`$$
CREATE PROCEDURE `insert_leftover_sku_mapping`( 
    IN leftoverid INT
    , IN skuid INT
    , IN no_of_units INT
    , IN totalWeight DECIMAL(6,2)
    , IN totalVolume DECIMAL(6,2)
    , IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT current_leftover_sku_mappingid INT
)
BEGIN
    IF (no_of_units != 0 &&  totalWeight != 0 && totalVolume != 0) THEN
        INSERT INTO leftover_sku_mapping( 
                                        leftoverid
                                        , skuid
                                        , no_of_units
                                        , totalWeight
                                        , totalVolume
                                        , customerno
                                        , created_on
                                        , updated_on 
                                        , created_by
                                        , updated_by
                                    )
        VALUES ( 
                    leftoverid
                    , skuid
                    , no_of_units
                    , totalWeight
                    , totalVolume
                    , customerno
                    , todaysdate
                    , todaysdate
                    , userid
                    , userid
                );
  
        SET current_leftover_sku_mappingid = LAST_INSERT_ID();
    END IF;
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
    DECLARE custno INT;
    DECLARE actshareidparam INT;
     
    UPDATE  transportershare
    SET     transporterid = transporterid
            , factoryid = factoryid
            , zoneid = zoneid
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
    WHERE   transporterid = transid
    AND     factoryid = factid
    AND     zoneid = zid
    AND     customerno = custno;
     
    IF (actshareidparam IS NULL OR actshareidparam = '' OR actshareidparam = 0) THEN
        CALL insert_transporteractualshare(transporterid, factoryid, zoneid, sharepercent, custno, todaysdate, userid);
    ELSE
        CALL update_transporteractualshare(transporterid, factoryid, zoneid, NULL, NULL, sharepercent, actshareidparam, custno, todaysdate, userid);
    END IF;
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
    , IN sharepercent decimal(6,2)
    , IN actshareidparam INT
    , IN custno INT
    , IN todaysdate DATETIME
    , IN userid INT
    )
BEGIN
    IF (sharedwt IS NULL AND totalwt IS NULL) THEN
        UPDATE `transporter_actualshare`
        SET      transporterid = transid
                , factoryid = factid
                , zoneid = zid
                , `actualpercent` = sharepercent
                , `updated_on` = todaysdate
                , `updated_by`= userid
        WHERE   actshareid= actshareidparam 
        AND     customerno = custno;
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
            AND     customerno = custno;
             
            SET     tempsharedwt = tempsharedwt + sharedwt;
            SET     temptotalwt = temptotalwt + totalwt;
             
            SET     actualsharepercent = (tempsharedwt/temptotalwt) * 100;
             
            UPDATE `transporter_actualshare`
            SET     `shared_weight` = tempsharedwt
                    ,`total_weight` = temptotalwt
                    , `actualpercent` = actualsharepercent
                    , `updated_on` = todaysdate
                    , `updated_by`= userid
            WHERE   transporterid = transid
            AND     factoryid = factid
            AND     zoneid = zid
            AND     customerno = custno;
        END;
    END IF;
END$$
DELIMITER ;
  
DELIMITER $$
DROP TRIGGER IF EXISTS before_transporteractualshare_update$$
CREATE TRIGGER before_transporteractualshare_update 
    BEFORE UPDATE ON transporter_actualshare
    FOR EACH ROW BEGIN
  
    INSERT INTO transporter_actualshare_history
    SET     actshareid      =   OLD.actshareid
            , factoryid     =   OLD.factoryid
            , zoneid        =   OLD.zoneid
            , transporterid =   OLD.transporterid
            , shared_weight =   OLD.shared_weight
            , total_weight  =   OLD.total_weight    
            , actualpercent =   OLD.actualpercent
            , customerno    =   OLD.customerno
            , created_on    =   OLD.created_on
            , updated_on    =   OLD.updated_on
            , created_by    =   OLD.created_by
            , updated_by    =   OLD.updated_by  
            , isdeleted     =   OLD.isdeleted
            , insertedDate  =   NOW();
END$$
DELIMITER ;
  
  
SET GLOBAL event_scheduler = ON;
DELIMITER $$
DROP EVENT IF EXISTS reset_transporteractualshare$$
CREATE EVENT `reset_transporteractualshare`
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
        UPDATE  transporter_actualshare 
        SET     shared_weight = 0
                ,total_weight = 0;
        COMMIT;
    END;
END$$
  
DROP PROCEDURE IF EXISTS `insert_trans_actualshare_history`;




DELIMITER $$
DROP PROCEDURE IF EXISTS `update_factory_delivery`$$
CREATE  PROCEDURE `update_factory_delivery`( 
	IN fdidparam int
	, IN factid int
	, IN skuidparam int
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
		BEGIN
			DECLARE grosswt decimal(7,3);
	    		DECLARE tempnetgross decimal(5,2);
	    
	    		SELECT  netgross
			INTO 	tempnetgross
		    	FROM 	sku
		    	WHERE 	skuid = skuidparam
		    	AND 	customerno = custno;
	    
	    		SET grosswt = weight + (weight * tempnetgross);

			UPDATE 	factory_delivery
			SET 	factoryid=factid
					,skuid = skuidparam
					,depotid = did
					,date_required = daterequired
					,netWeight = weight
					,grossWeight = grosswt
					,updated_on = todaysdate 
					, updated_by = userid
			WHERE 	fdid = fdidparam;
		END;
	END IF;

END$$
DELIMITER ;











