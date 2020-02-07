DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_trip_consignee`$$
CREATE  PROCEDURE `insert_trip_consignee`(
    IN consigneeNameParam VARCHAR(50)
    , IN emailParam VARCHAR(50)
    , IN phoneParam VARCHAR(15)
    , IN chkPtIdParam INT
    , IN customerNoParam INT
    , OUT currentConsigneeId INT
    )
BEGIN
    DECLARE isConsigneeExists INT;
    SELECT consid
    INTO isConsigneeExists
    FROM tripconsignee
    WHERE TRIM(REPLACE(consigneename, " ", "")) = TRIM(REPLACE(REPLACE(UPPER(consigneeNameParam), " ", ""),"'",""))
    AND customerno = customerNoParam
    AND isdeleted = 0 LIMIT 1;
    IF(isConsigneeExists IS NULL)THEN
        INSERT INTO tripconsignee(
            consigneename
            , email
            , phone
            , checkpointid
            , customerno
        )
        VALUES (
            consigneeNameParam
            , emailParam
            , phoneParam
            , chkPtIdParam
            , customerNoParam
        );
        SET currentConsigneeId = LAST_INSERT_ID();
    ELSE
        SET currentConsigneeId = isConsigneeExists;
    END IF;
END$$
DELIMITER ;
