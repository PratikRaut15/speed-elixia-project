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
/*
CALL merge_delivery_challan('1','5465465','2018-07-25 10:10:10','654654','2018-07-25 10:10:10','54654654','454654','2018-07-25 10:10:10','7989799898','564654','54654','656465','654654','545','6545','4654','4654','4654','asf','dfsdf','sdf','546','sdf','dsfsdf','sdfsdf','sdfsdf usdf yyy sdyf tysdf ysdttff tsdsy uyufyt tfd','B5454','447','6991','2018-07-25 20:59:06','0',@insertChallanId);
SELECT @insertChallanId;
*/