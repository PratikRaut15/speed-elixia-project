DELIMITER $$
DROP PROCEDURE IF EXISTS update_transportershare$$
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