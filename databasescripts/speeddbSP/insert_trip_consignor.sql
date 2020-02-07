DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_trip_consignor`$$
CREATE  PROCEDURE `insert_trip_consignor`(
    IN consignorNameParam VARCHAR(50)
    , IN emailParam VARCHAR(50)
    , IN phoneParam VARCHAR(15)
    , IN chkPtIdParam INT
    , IN customerNoParam INT
    , OUT currentConsignorId INT
    )
BEGIN
    DECLARE isConsignorExists INT;
    SELECT consrid
    INTO isConsignorExists
    FROM tripconsignor
    WHERE TRIM(REPLACE(consignorname, " ", "")) = TRIM(REPLACE(REPLACE(UPPER(consignorNameParam), " ", ""),"'",""))
    AND customerno = customerNoParam
    AND isdeleted = 0 LIMIT 1;
    IF(isConsignorExists IS NULL)THEN
        INSERT INTO tripconsignor(
            consignorname
            , email
            , phone
            , checkpointid
            , customerno
        )
        VALUES (
            consignorNameParam
            , emailParam
            , phoneParam
            , chkPtIdParam
            , customerNoParam
        );
        SET currentConsignorId = LAST_INSERT_ID();
    ELSE
        SET currentConsignorId = isConsignorExists;
    END IF;
END$$
DELIMITER ;
CALL insert_trip_consignor('Test','','','0','447',@currentConsignorId)
