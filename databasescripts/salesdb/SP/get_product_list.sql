DELIMITER $$
DROP PROCEDURE IF EXISTS `get_product_list`$$
CREATE PROCEDURE `get_product_list`(
    IN productIdParam INT  
    ,IN franchiseIdParam INT
    ,IN searchStringParam VARCHAR(50)
    ,IN customerIdParam INT
    ,IN pageIndex INT
    ,IN pageSize INT
    ,IN userIdParam INT
    ,IN customerNoParam INT
)
BEGIN
  DECLARE recordCount INT;
    DECLARE fromRowNum INT DEFAULT 1;
    DECLARE toRowNum INT DEFAULT 1;
    DECLARE serialNo INT;

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
        SET @serialNO=0;


        IF (customerNoParam = 0) THEN
            SET customerNoParam = NULL;
        END IF;
        IF (franchiseIdParam = 0) THEN
            SET franchiseIdParam = NULL;
        END IF;

        IF (customerIdParam = '') THEN
            SET customerIdParam = NULL;
        END IF;
        IF (franchiseIdParam = 0) THEN
            SET franchiseIdParam = NULL;
        END IF;
        IF (productIdParam = 0) THEN
            SET productIdParam = NULL;
        END IF;
 -- select searchStringParam;
        IF (customerNoParam IS NOT NULL AND franchiseIdParam IS NOT NULL ) THEN
      SET recordCount =  (
                SELECT COUNT(p.styleid)
                FROM style p
                INNER JOIN franchise f ON f.franchiseId = p.franchiseId
                LEFT JOIN packingMaster pm on pm.packingId = p.packingId and pm.isDeleted = 0
                LEFT JOIN customerSkuRateChart rc on rc.skuId = p.styleid AND (rc.customerId = customerIdParam AND customerIdParam IS NOT NULL) and rc.isDeleted = 0
                WHERE (p.styleid = productIdParam OR productIdParam IS NULL)
                AND (p.franchiseId = franchiseIdParam OR franchiseIdParam IS NULL)
                AND (p.styleno LIKE CONCAT('%', searchStringParam, '%') OR searchStringParam IS NULL)
                AND (p.customerno = customerNoParam OR customerNoParam IS NULL)
                AND p.isdeleted = 0);


            IF (pageSize = -1) THEN
                    SET pageSize = recordCount;
            END IF;

            SET fromRowNum = (pageIndex - 1) * pageSize + 1;
            SET toRowNum = (fromRowNum + pageSize) - 1;
            SET @rownum = 0;

            SELECT  *, recordCount
                FROM  (SELECT  @rownum:=@rownum + 1 AS rownum, products.*
                        FROM (
                SELECT
                   @serialNo := @serialNo+1 AS `serialNo`,
                   p.styleid as productId
                  ,p.categoryId
                  ,p.franchiseId
                  ,p.salesLineId
                  ,p.lineOfBusinessId
                  ,p.styleno as productName

                  ,p.distprice as distprice
                  ,p.retailprice as retailPrice
                  ,p.carton
                  ,p.productimage as productImage
                  ,p.macro
                  ,p.packs
                  ,p.micro
                  ,p.uom
                  ,COALESCE(rc.rate, p.mrp) as mrp
                  ,pm.packingType
                  ,p.customerno
                FROM style p
                INNER JOIN franchise f ON f.franchiseId = p.franchiseId
                LEFT JOIN packingMaster pm on pm.packingId = p.packingId and pm.isDeleted = 0
                LEFT JOIN customerSkuRateChart rc on rc.skuId = p.styleid AND (rc.customerId = customerIdParam AND customerIdParam IS NOT NULL) and rc.isDeleted = 0
                WHERE (p.styleid = productIdParam OR productIdParam IS NULL) 
                AND (p.franchiseId = franchiseIdParam OR franchiseIdParam IS NULL)
                AND (p.styleno LIKE CONCAT('%', searchStringParam, '%') OR searchStringParam IS NULL)
                AND (p.customerno = customerNoParam OR customerNoParam IS NULL)
                AND p.isdeleted = 0
                                )products
                             )products
        WHERE   rownum BETWEEN fromRowNum AND toRowNum
                ORDER BY  rownum;
        END IF;

END$$
DELIMITER ;
CALL get_product_list(0,'1','',51918,'1','10','','698');
