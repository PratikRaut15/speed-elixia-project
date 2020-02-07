INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'563', '2018-05-025 14:00:00', 'Shrikant Suryawanshi', 'SBM API', '0'
);





ALTER TABLE `tripdetails` ADD `orderno` VARCHAR(25) NOT NULL AFTER `materialtype`, ADD `orderdatetime` DATETIME NOT NULL AFTER `orderno`;
ALTER TABLE `tripdetail_history` ADD `orderno` VARCHAR(25) NOT NULL AFTER `materialtype`, ADD `orderdatetime` DATETIME NOT NULL AFTER `orderno`;


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


DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_trip_lr_mapping`$$
CREATE  PROCEDURE `insert_trip_lr_mapping`(
    IN tripIdParam INT
    , IN lrNoParam VARCHAR(25)
    , IN lrDateTimeParam DATETIME
    , IN consignorIdParam INT
    , IN consigneeIdParam INT
    , IN customerNoParam INT
    , IN userIdParam INT
    , IN todaysDate DATETIME
    )
BEGIN




        INSERT INTO triplrmapping(
            tripid
            , lrno
            , lrdatetime
            , lrconsignorid
            , lrconsigneeid
            , customerno
            , created_by
            , created_on
        )
        VALUES (
            tripIdParam
            , lrNoParam
            , lrDateTimeParam
            , consignorIdParam
            , consigneeIdParam
            , customerNoParam
            , userIdParam
            , todaysDate
        );


END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_trip_droppoints`$$
CREATE PROCEDURE `get_trip_droppoints`(
    IN tripIdParam INT,
    IN customerNoParam INT
)
BEGIN

    IF(tripIdParam = 0) THEN
        SET tripIdParam = NULL;
    END IF;

    IF(customerNoParam = 0) THEN
        SET customerNoParam = NULL;
    END IF;


    SELECT
            dp.tripid,
            dp.vehicleid,
            dp.lat,
            dp.lng,
            dp.customerno
    FROM    tripdroppoints dp
    WHERE   (dp.tripId = tripIdParam OR tripIdParam IS NULL)
    AND     (dp.customerno = customerNoParam OR customerNoParam IS NULL)
    AND     dp.isdeleted = 0;

END$$
DELIMITER ;

CALL get_trip_droppoints(16472,447);
