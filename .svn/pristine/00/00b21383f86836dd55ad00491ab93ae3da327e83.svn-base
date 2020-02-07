DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_transportershare`$$
CREATE  PROCEDURE `delete_transportershare`(
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
		AND     customerno = custno
		AND     isdeleted  = 0
		limit 1;
		
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
