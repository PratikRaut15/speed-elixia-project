INSERT INTO `speed`.`dbpatches`(
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'470', '2017-02-27 12:00:00', 'Ganesh Papde', 'Pullvehicle list fms sp', '0'
);

DELIMITER $$
DROP PROCEDURE IF EXISTS `getVehicleDetails_fms`$$
CREATE PROCEDURE `getVehicleDetails_fms`(
     IN pageIndex INT, 
     IN pageSize INT , 
     IN custnoparam INT , 
     IN searchStringParam VARCHAR(40), 
     IN categoryIdParam TINYINT, 
     IN expiryDateParam date, 
     IN groupIdParam VARCHAR(250)
)
BEGIN
    DECLARE recordCount INT;
	DECLARE fromRowNum INT DEFAULT 1;
	DECLARE toRowNum INT DEFAULT 1;
    
    IF LTRIM(RTRIM(searchStringParam)) = '' THEN 
		SET searchStringParam = NULL;
	ELSE
        SET searchStringParam = LTRIM(RTRIM(searchStringParam));
	END IF;
    
    IF groupIdParam = '0' OR  groupIdParam = '' THEN
		SET groupIdParam = NULL;
    END IF;
    
    IF expiryDateParam = '' OR expiryDateParam = '0000-00-00' THEN
		SET expiryDateParam = NULL;
    END IF;
    
    IF categoryIdParam = 0 THEN
		SET categoryIdParam = NULL;
    END IF;
    SET recordCount =  (SELECT 		COUNT(v.vehicleid) 
						FROM 				vehicle v
						LEFT OUTER JOIN  	valert va on va.vehicleid = v.vehicleid AND va.customerno=custnoparam
						LEFT OUTER JOIN  	tax on tax.vehicleid = v.vehicleid AND tax.isdeleted=0 AND tax.customerno=custnoparam
						LEFT OUTER JOIN 	`group` g ON g.groupid=v.groupid 
						LEFT OUTER JOIN 	description des ON des.vehicleid = v.vehicleid
						WHERE 				v.customerno = custnoparam
						AND 				v.isdeleted=0
						AND 				(FIND_IN_SET(v.groupid, groupIdParam) OR groupIdParam IS NULL)
						AND 				(v.vehicleno LIKE CONCAT('%', searchStringParam ,'%') OR searchStringParam IS NULL)
						AND 				(
												categoryIdParam IS NULL OR 
												(CASE categoryIdParam 
													WHEN 1 THEN tax.to_date<>'0000-00-00 00:00:00'
													WHEN 2 THEN va.puc_expiry<>'0000-00-00 00:00:00'
													WHEN 3 THEN va.insurance_expiry<>'0000-00-00 00:00:00'
													ELSE 1
												END)
											)
						AND 				(
												expiryDateParam IS NULL OR
												(CASE 
													WHEN COALESCE(categoryIdParam,0) = 1	THEN 	DATE(tax.to_date) < expiryDateParam
													WHEN COALESCE(categoryIdParam,0) = 2 	THEN 	DATE(va.puc_expiry) < expiryDateParam
													WHEN COALESCE(categoryIdParam,0) = 3 	THEN 	DATE(va.insurance_expiry) < expiryDateParam
													WHEN searchStringParam IS NULL 			THEN 	(DATE(tax.to_date) < expiryDateParam 
																									OR DATE(va.puc_expiry) < expiryDateParam
																									OR DATE(va.insurance_expiry) < expiryDateParam)
												END)
											)
						ORDER BY 			v.vehicleno ASC
                        );
    
    IF (pageSize = -1) THEN
		SET pageSize = recordCount;
    END IF;
    
	SET fromRowNum = (pageIndex - 1) * pageSize + 1;
	SET toRowNum = (fromRowNum + pageSize) - 1;
	SET @rownum = 0;
    SELECT	*, recordCount
	FROM 	(SELECT  @rownum:=@rownum + 1 AS rownum, vehDetails.*
			 FROM	(SELECT 	
						v.vehicleno,
						v.vehicleid,
						des.seatcapacity,
						v.groupid,
						v.puc_filename,
						v.registration_filename,
						v.insurance_filename,
						v.other_upload1,
						v.other_upload2,
						v.other_upload3,
						v.other_upload4,
						v.other_upload5,
						v.other_upload6,
						tax.from_date,
						tax.to_date, 
						va.puc_expiry,
						va.reg_expiry,
						va.insurance_expiry,
						va.other1_expiry,
						va.other2_expiry, 
						va.other3_expiry,
						va.other4_expiry,
						va.other5_expiry,
						va.other6_expiry,
						g.groupname				
				FROM 				vehicle v
				LEFT OUTER JOIN  	valert va on va.vehicleid = v.vehicleid AND va.customerno=custnoparam
				LEFT OUTER JOIN  	tax on tax.vehicleid = v.vehicleid AND tax.isdeleted=0 AND tax.customerno=custnoparam
				LEFT OUTER JOIN 	`group` g ON g.groupid=v.groupid 
				LEFT OUTER JOIN 	description des ON des.vehicleid = v.vehicleid
				WHERE 				v.customerno = custnoparam
				AND 				v.isdeleted=0
				AND 				(FIND_IN_SET(v.groupid, groupIdParam) OR groupIdParam IS NULL)
				AND 				(v.vehicleno LIKE CONCAT('%', searchStringParam ,'%') OR searchStringParam IS NULL)
				AND 				(
										categoryIdParam IS NULL OR 
										(CASE categoryIdParam 
											WHEN 1 THEN tax.to_date<>'0000-00-00 00:00:00'
											WHEN 2 THEN va.puc_expiry<>'0000-00-00 00:00:00'
											WHEN 3 THEN va.insurance_expiry<>'0000-00-00 00:00:00'
											ELSE 1
										END)
									)
				AND 				(
										expiryDateParam IS NULL OR
										(CASE 
											WHEN COALESCE(categoryIdParam,0) = 1	THEN 	DATE(tax.to_date) < expiryDateParam
											WHEN COALESCE(categoryIdParam,0) = 2 	THEN 	DATE(va.puc_expiry) < expiryDateParam
											WHEN COALESCE(categoryIdParam,0) = 3 	THEN 	DATE(va.insurance_expiry) < expiryDateParam
											WHEN searchStringParam IS NULL 			THEN 	(DATE(tax.to_date) < expiryDateParam 
																							OR DATE(va.puc_expiry) < expiryDateParam
																							OR DATE(va.insurance_expiry) < expiryDateParam)
										END)
									)
				ORDER BY 			v.vehicleno ASC
					) vehDetails)vehDetails
	WHERE		rownum BETWEEN fromRowNum AND toRowNum
    ORDER BY 	rownum;

END$$
DELIMITER ;


UPDATE  dbpatches
SET     patchdate = '2017-02-27 12:00:00'
        ,isapplied =1
WHERE   patchid = 470;
