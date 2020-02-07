
Create index index_transporterid on transportershare (transporterid);

Create index index_transporterid_share on transporter_actualshare (transporterid);

Create index index_transporterid_vehicle_mapping on vehtypetransmapping (transporterid);


DELIMITER $$
DROP PROCEDURE IF EXISTS delete_transportershare$$
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
    
	IF(currenttransportershareid IS NOT NULL) THEN
    BEGIN
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
		AND     customerno = custno
        AND 	isdeleted = 0;
		
	IF (actshareidparam IS NOT NULL) THEN
			CALL delete_transporteractualshare(transporteridparam, actshareidparam, custno, todaysdate, userid);
	ELSEIF(currenttransporterid IS NOT NULL) THEN
		CALL delete_transporteractualshare(transporteridparam, NULL, custno, todaysdate, userid);
    END IF; 
    
	END;
    END IF;
    
   
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

    SELECT  customerno 
    INTO    custno
    FROM    transportershare
    WHERE   transportershareid = transhareid;
     
    UPDATE  transportershare
    SET     transporterid = transporteridparam
            , factoryid = factoryidparam
            , zoneid = zoneidparam
            , sharepercent = sharepercent
            , updated_on = todaysdate
            , updated_by = userid
    WHERE   transportershareid = transhareid
    AND		customerno = custno;
    
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS delete_transporter$$
CREATE PROCEDURE `delete_transporter`(
	IN tranid INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
     BEGIN
       -- ERROR
     ROLLBACK;
   END;
   
    START TRANSACTION;
    BEGIN


	UPDATE transporter 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE transporterid = tranid;
    
	CALL delete_transportershare(NULL, tranid, todaysdate, userid);    
	CALL delete_vehtypetransporter_mapping(NULL, tranid, todaysdate, userid);
    
    
	COMMIT;
    END;
END$$
DELIMITER ;


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
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
     BEGIN
       -- ERROR
     ROLLBACK;
	END;
    
    
	IF (currenttransportershareid = '' OR currenttransportershareid = 0) THEN
		SET currenttransportershareid = NULL;
    END IF;
    
    IF (currenttransporterid = '' OR currenttransporterid = 0) THEN
		SET currenttransporterid = NULL;
    END IF;
    
    START TRANSACTION;
    BEGIN
    
	UPDATE transportershare 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE 	(transportershareid = currenttransportershareid OR currenttransportershareid IS NULL)
    AND		(transporterid = currenttransporterid OR currenttransporterid IS NULL)
    AND     isdeleted = 0;
	
        
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
		ELSEIF(currenttransporterid IS NOT NULL) THEN
			CALL delete_transporteractualshare(transporteridparam, NULL, custno, todaysdate, userid);
		END IF;
    ELSEIF(currenttransporterid IS NOT NULL) THEN
		SELECT  customerno
		INTO    custno
		FROM    transportershare
		WHERE   transporterid = currenttransporterid LIMIT 1;
        
		CALL delete_transporteractualshare(currenttransporterid, NULL, custno, todaysdate, userid);
		
    END IF;
    
	COMMIT;
    END;
END$$
DELIMITER ;



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
        AND     customerno = custno
        AND 	isdeleted = 0;
END$$
DELIMITER ;



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 5, NOW(), 'Shrikant Suryawanshi','Transporter Share Percentage Mapping');
