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
