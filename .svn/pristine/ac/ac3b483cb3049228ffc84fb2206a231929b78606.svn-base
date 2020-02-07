DELIMITER $$
DROP PROCEDURE IF EXISTS `get_offers`$$
CREATE PROCEDURE `get_offers`(
    IN offerIdParam INT
    , IN productIdParam INT
    , IN todaysDate DATE
    , IN customerNoParam INT
)
BEGIN
  
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
        IF (offerIdParam = 0) THEN
            SET offerIdParam = NULL;
        END IF;
        IF (productIdParam = 0) THEN
            SET productIdParam = NULL;
        END IF;
        IF (todaysDate = '0000-00-00') THEN
            SET productIdParam = NULL;
        END IF;

        
        IF (customerNoParam IS NOT NULL ) THEN
            
            Select 
              po.offerId,
              po.productId,
              po.minQty,
              po.promoProductId,
              po.promoQty,
              po.description,
              po.discount,
              p.styleno as productName
            FROM promoOffers po
            INNER JOIN style p on p.styleid = po.promoProductId 
            WHERE (po.offerId = offerIdParam OR offerIdParam IS NULL)
            AND (po.productId = productIdParam OR productIdParam IS NULL)
            AND po.customerNo = customerNoParam
            AND todaysDate between offerStartDate and offerEndDate
            AND po.isDeleted = 0 ;



        END IF;

END$$
DELIMITER ;
CALL get_offers(1,233,'2019-08-01',698);
