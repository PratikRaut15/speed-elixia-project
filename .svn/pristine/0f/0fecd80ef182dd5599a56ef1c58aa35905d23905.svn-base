INSERT INTO dbpatches (patchid, patchdate, appliedby, patchdesc)
VALUES ('27', '2018-02-22 19:00:00', 'Sanjeet Shukla', 'Vora Enterprise -change get_shops_by_sr');

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
				,CASE WHEN(e.shopid IS NULL) THEN '0' ELSE '1' END AS is_entryexists
			FROM shop AS s 
            INNER JOIN speed.user u ON u.userId = s.salesid AND u.isdeleted = 0
			LEFT OUTER JOIN shopDaySrMapping sdrm ON sdrm.shopId = s.shopid AND sdrm.isDeleted = 0
			LEFT OUTER JOIN shoptype AS st ON st.shid = s.shoptypeid AND st.isdeleted = 0
			LEFT OUTER JOIN area AS a ON a.areaid = s.areaid AND a.isdeleted = 0
			LEFT OUTER JOIN entry AS e on e.salesid = userIdParam AND e.shopid = 
			(select ee.shopid from entry ee Where ee.customerno = customerNoParam AND ee.shopid = s.shopid AND DATE(ee.entrytime) = DATE(curdate()) group by ee.shopid)
			WHERE	(s.salesid = userIdParam)
			AND 	(sdrm.dayId = dayIdParam OR dayIdParam IS NULL)
			AND 	(s.shopname LIKE CONCAT('%', searchStringParam, '%') OR searchStringParam IS NULL)
			AND 	s.customerno = customerNoParam 
			AND 	s.isdeleted=0 
            GROUP BY s.shopid
			ORDER BY s.sequence_no ASC;

        END IF;

END$$
DELIMITER ;

END$$
DELIMITER ;

