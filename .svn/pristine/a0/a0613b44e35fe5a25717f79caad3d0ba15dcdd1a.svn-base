INSERT INTO dbpatches (patchid, patchdate, appliedby, patchdesc)
VALUES ('26', '2018-02-19 14:30:00', 'Sanjeet Shukla', 'Vora Enterprise - Secondary Sales Sp Change');

update elixiatech.customer set use_erp = 1 where customerNo = 698;

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_shops_by_sr`$$
CREATE PROCEDURE `get_shops_by_sr`(
    IN userIdParam INT
    ,IN dayIdParam INT
    ,IN searchStringParam VARCHAR(50)
    ,IN customerNoParam INT
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
        IF (userIdParam = 0) THEN
            SET userIdParam = NULL;
        END IF;

        IF (dayIdParam = -1) THEN
            SET dayIdParam = NULL;
        END IF;
        IF (searchStringParam = '') THEN
            SET searchStringParam = NULL;
        END IF;

        IF (customerNoParam IS NOT NULL AND userIdParam IS NOT NULL ) THEN
            -- SELECT
--                  s.shopid as shopId
--             FROM shop s
--             INNER JOIN speed.user u ON u.userId = s.salesid
--             WHERE (s.salesid = userIdParam)
--             AND (s.customerno = customerNoParam)
--             AND s.isdeleted = 0;

            SELECT
				sdrm.dayId
				,s.shopid
				,s.salesid
				,a.areaname
				,st.shop_type
				,s.shoptypeid
				,s.phone
				,s.phone2
				,s.dob
				,s.owner
				,s.owner_shop
				,s.address
				,s.emailid
				,s.shopname
				,s.areaid
				,s.sequence_no
				,s.salesid
				,s.distributorid
			FROM shop AS s
            INNER JOIN speed.user u ON u.userId = s.salesid AND u.isdeleted = 0
			LEFT OUTER JOIN shopDaySrMapping sdrm ON sdrm.shopId = s.shopid AND sdrm.isDeleted = 0
			LEFT OUTER JOIN shoptype AS st ON st.shid = s.shoptypeid AND st.isdeleted = 0
			LEFT OUTER JOIN area AS a ON a.areaid = s.areaid AND a.isdeleted = 0
			WHERE	(s.salesid = userIdParam)
			AND 	(sdrm.dayId = dayIdParam OR dayIdParam IS NULL)
			AND 	(s.shopname LIKE CONCAT('%', searchStringParam, '%') OR searchStringParam IS NULL)
			AND 	s.customerno = customerNoParam
			AND 	s.isdeleted=0
			ORDER BY s.sequence_no ASC;

        END IF;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_product_list`$$
CREATE PROCEDURE `get_product_list`(
    IN franchiseIdParam INT
    ,IN searchStringParam VARCHAR(50)
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

        IF (searchStringParam = '') THEN
            SET searchStringParam = NULL;
        END IF;
 -- select searchStringParam;
        IF (customerNoParam IS NOT NULL AND franchiseIdParam IS NOT NULL ) THEN
			SET recordCount =  (
								SELECT COUNT(p.styleid)
								FROM style p
								INNER JOIN franchise f ON f.franchiseId = p.franchiseId
								WHERE (p.franchiseId = franchiseIdParam OR franchiseIdParam IS NULL)
								AND	(p.styleno LIKE CONCAT('%', searchStringParam, '%') OR searchStringParam IS NULL)
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
									,p.mrp
									,p.distprice as distprice
									,p.retailprice as retailPrice
									,p.carton
									,p.productimage as productImage
									,p.customerno
								FROM style p
								INNER JOIN franchise f ON f.franchiseId = p.franchiseId
								WHERE (p.franchiseId = franchiseIdParam OR franchiseIdParam IS NULL)
								AND	(p.styleno LIKE CONCAT('%', searchStringParam, '%') OR searchStringParam IS NULL)
								AND (p.customerno = customerNoParam OR customerNoParam IS NULL)
								AND p.isdeleted = 0
                                )products
                             )products
				WHERE   rownum BETWEEN fromRowNum AND toRowNum
                ORDER BY  rownum;
        END IF;

END$$
DELIMITER ;

