INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'633', '2018-11-05 16:30:00', 'Manasvi Thakur','To Update order details and delivery challan SP', '0');

DELIMITER $$
DROP PROCEDURE IF EXISTS `merge_order_details`$$
CREATE PROCEDURE `merge_order_details`(
    IN tripIdParam INT,
    IN chitthiNoParam VARCHAR(8),
    IN chitthiDateParam DATETIME,
    IN tokenNoParam VARCHAR(6),
    IN custCodeParam VARCHAR(6),
    IN rakeNoParam VARCHAR(15),
    IN brandCodeParam VARCHAR(6),
    IN bagsParam float,
    IN truckNoParam VARCHAR(15),
    IN delTypeParam VARCHAR(6),
    IN qtyTypeParam VARCHAR(7),
    IN stockyCodeParam VARCHAR(6),
    IN customerNoParam INT,
    IN userIdParam INT,
    IN todaysDate DATETIME,
    IN isDeletedParam TINYINT(2),
    OUT insertedOrderId INT
)
BEGIN
    DECLARE isChitthiNoExists VARCHAR(8);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */
    END;
    IF (customerNoParam = 0) THEN
        SET customerNoParam = NULL;
    END IF;
    IF (tripIdParam = 0) THEN
        SET tripIdParam = NULL;
    END IF;
    IF (chitthiNoParam = '') THEN
        SET chitthiNoParam = NULL;
    END IF;
    IF (tokenNoParam = '') THEN
        SET tokenNoParam = NULL;
    END IF;
    IF ( custCodeParam = '') THEN
        SET custCodeParam = NULL;
    END IF;
    IF (rakeNoParam = '') THEN
        SET rakeNoParam = NULL;
    END IF;
    IF ( brandCodeParam = '') THEN
        SET brandCodeParam = NULL;
    END IF;
    IF ( truckNoParam = '') THEN
        SET truckNoParam = NULL;
    END IF;
    IF (delTypeParam = '') THEN
        SET delTypeParam = NULL;
    END IF;
    IF (qtyTypeParam = '') THEN
        SET qtyTypeParam = NULL;
    END IF;
    IF (stockyCodeParam = '') THEN
        SET stockyCodeParam = NULL;
    END IF;
    /* Check If Chitti No Already Exists Or Not  */
    SELECT  orderId
    INTO    isChitthiNoExists
    FROM    orderDetails
    WHERE   customerno  = customerNoParam
    AND     tripId      = tripIdParam
    AND     chitthiNo   = chitthiNoParam
    AND     isDeleted   = 0
    LIMIT 1;
    START TRANSACTION;
    IF (customerNoParam IS NOT NULL ) THEN
        IF(isChitthiNoExists IS NOT NULL)THEN
            SET insertedOrderId = isChitthiNoExists;
            UPDATE orderDetails
            SET chitthiDate = chitthiDate,
                tokenNo = tokenNoParam,
                custCode = custCodeParam,
                rakeNo =rakeNoParam,
                brandCode =brandCodeParam,
                bags=bagsParam,
                truckNo =truckNoParam,
                delType =delTypeParam,
                qtyType=qtyTypeParam,
                stockCode = stockyCodeParam,
                updated_by = userIdParam,
                updated_on = todaysDate,
                isDeleted = isDeletedParam
                WHERE customerno =customerNoParam 
                AND tripId =tripIdParam 
                AND chitthiNo =chitthiNoParam;
            # To Do
            # Merge Order Details Code Here
        ELSE
            INSERT INTO orderDetails(
                tripId,
                chitthiNo,
                chitthiDate,
                tokenNo,
                custCode,
                rakeNo,
                brandCode,
                bags,
                truckNo,
                delType,
                qtyType,
                stockCode,
                customerno,
                created_by,
                created_on
            )VALUES(
                tripIdParam,
                chitthiNoParam,
                chitthiDateParam,
                tokenNoParam,
                custCodeParam,
                rakeNoParam,
                brandCodeParam,
                bagsParam,
                truckNoParam,
                delTypeParam,
                qtyTypeParam,
                stockyCodeParam,
                customerNoParam,
                userIdParam,
                todaysDate
            );
            SET insertedOrderId = LAST_INSERT_ID();
        END IF;
    END IF;

    COMMIT;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `merge_delivery_challan`$$
CREATE PROCEDURE `merge_delivery_challan`(
    IN orderIdParam INT,
    IN delNoParam VARCHAR(10),
    IN delDateParam DATETIME,
    IN chlNoParam VARCHAR(15),
    IN chlDateParam DATETIME,
    IN diNoParam VARCHAR(10),
    IN sapNoParam VARCHAR(10),
    IN sapDateParam DATETIME,
    IN lrNoParam VARCHAR(10),
    IN invNoParam VARCHAR(12),
    IN delFromParam VARCHAR(6),
    IN qtyTypeParam VARCHAR(7),
    IN delTypeParam VARCHAR(6),
    IN bCoCodeParam VARCHAR(6),
    IN delSiteParam VARCHAR(6),
    IN saleTypeParam VARCHAR(10),
    IN endoCodeParam VARCHAR(6),
    IN salesProCodeParam VARCHAR(6),
    IN stockyCodeParam VARCHAR(6),
    IN clientCodeParam VARCHAR(6),
    IN siteCodeParam VARCHAR(6),
    IN frgtRateParam float,
    IN placeCodeParam VARCHAR(6),
    IN loadingParam VARCHAR(12),
    IN tpvPlacecodeParam VARCHAR(6),
    IN rem1Param TEXT,
    IN transpBillNoParam VARCHAR(15),
    IN customerNoParam INT,
    IN userIdParam INT,
    IN todaysDate DATETIME,
    IN isDeletedParam TINYINT(2),
    OUT insertChallanId INT
)
BEGIN
    DECLARE isLrNoExists VARCHAR(8);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */
    END;
    IF (customerNoParam = 0) THEN
        SET customerNoParam = NULL;
    END IF;
    IF (orderIdParam = 0) THEN
        SET orderIdParam = NULL;
    END IF;
    IF (delNoParam = '') THEN
        SET delNoParam = NULL;
    END IF;
    IF (chlNoParam = '') THEN
        SET chlNoParam = NULL;
    END IF;
    IF ( diNoParam = '') THEN
        SET diNoParam = NULL;
    END IF;
    IF (sapNoParam = '') THEN
        SET sapNoParam = NULL;
    END IF;
    IF ( lrNoParam = '') THEN
        SET lrNoParam = NULL;
    END IF;
    IF ( invNoParam = '') THEN
        SET invNoParam = NULL;
    END IF;
    IF (delFromParam = '') THEN
        SET delFromParam = NULL;
    END IF;
    IF (qtyTypeParam = '') THEN
        SET qtyTypeParam = NULL;
    END IF;
    IF (delTypeParam = '') THEN
        SET delTypeParam = NULL;
    END IF;

    IF ( bCoCodeParam= '') THEN
        SET bCoCodeParam = NULL;
    END IF;
    IF ( delSiteParam= '') THEN
        SET  delSiteParam= NULL;
    END IF;
    IF ( saleTypeParam= '') THEN
        SET  saleTypeParam= NULL;
    END IF;
    IF ( stockyCodeParam = '') THEN
        SET  stockyCodeParam = NULL;
    END IF;
    IF (clientCodeParam = '') THEN
        SET clientCodeParam = NULL;
    END IF;
    IF (siteCodeParam = '') THEN
        SET siteCodeParam = NULL;
    END IF;
    IF (placeCodeParam = '') THEN
        SET placeCodeParam = NULL;
    END IF;
    IF ( loadingParam = '') THEN
        SET loadingParam = NULL;
    END IF;
    IF (tpvPlacecodeParam = '') THEN
        SET tpvPlacecodeParam = NULL;
    END IF;
    IF ( rem1Param= '') THEN
        SET  rem1Param= NULL;
    END IF;

    IF ( transpBillNoParam= '') THEN
        SET  transpBillNoParam= NULL;
    END IF;

    /* Check If Chitti No Already Exists Or Not  */
    SELECT  deliveryChallanId
    INTO    isLrNoExists
    FROM    deliveryChallan
    WHERE   customerno = customerNoParam
    AND     orderId = orderIdParam
    AND     lrNo = lrNoParam
    AND     isDeleted = 0
    LIMIT 1;
    START TRANSACTION;
    IF (customerNoParam IS NOT NULL) THEN
        IF(isLrNoExists IS NOT NULL)THEN
            # To Do
            # Merge Order Details Code Here
            SET insertChallanId = isLrNoExists;

            UPDATE deliveryChallan
            SET delDate = delDateParam,
                chlDate = chlDateParam,
                diNo =diNoParam,
                sapNo = sapNoParam,
                sapDate = sapDateParam,
                lrNo = lrNoParam,
                invNo =invNoParam,
                delFrom =delFromParam,
                qtyType = qtyTypeParam,
                delType =delTypeParam,
                bCoCode =bCoCodeParam,
                saleType =saleTypeParam,
                endoCode =endoCodeParam,
                salesProCode=salesProCodeParam,
                stockyCode=stockyCodeParam,
                clientCode=clientCodeParam,
                siteCode=siteCodeParam,
                frgtRate=frgtRateParam,
                placeCode=placeCodeParam,
                loading=loadingParam,
                tpvPlaceCode=tpvPlaceCodeParam,
                rem1=rem1Param,
                transpBillNo=transpBillNoParam,
                updated_by=userIdParam,
                updated_on=todaysDate,
                isDeleted = isDeletedParam
            WHERE customerno =customerNoParam 
                AND orderId =orderIdParam
                AND deliveryChallanId = insertChallanId;
        ELSE
            INSERT INTO deliveryChallan(
                orderId,
                delNo,
                delDate,
                chlNo,
                chlDate,
                diNo,
                sapNo,
                sapDate,
                lrNo,
                invNo,
                delFrom,
                qtyType,
                delType,
                bCoCode,
                delSite,
                saleType,
                endoCode,
                salesProCode,
                stockyCode,
                clientCode,
                siteCode,
                frgtRate,
                placeCode,
                loading,
                tpvPlaceCode,
                rem1,
                transpBillNo,
                customerno,
                created_by,
                created_on
            )VALUES(
                orderIdParam,
                delNoParam,
                delDateParam,
                chlNoParam,
                chlDateParam,
                diNoParam,
                sapNoParam,
                sapDateParam,
                lrNoParam,
                invNoParam,
                delFromParam,
                qtyTypeParam,
                delTypeParam,
                bCoCodeParam,
                delSiteParam,
                saleTypeParam,
                endoCodeParam,
                salesProCodeParam,
                stockyCodeParam,
                clientCodeParam,
                siteCodeParam,
                frgtRateParam,
                placeCodeParam,
                loadingParam,
                tpvPlaceCodeParam,
                rem1Param,
                transpBillNoParam,
                customerNoParam,
                userIdParam,
                todaysDate
            );
            SET insertChallanId = LAST_INSERT_ID();

        END IF;
    END IF;

    COMMIT;
END$$
DELIMITER ;


UPDATE  dbpatches
SET     patchdate = '2018-11-05 16:30:00'
        ,isapplied =1
WHERE   patchid = 633;