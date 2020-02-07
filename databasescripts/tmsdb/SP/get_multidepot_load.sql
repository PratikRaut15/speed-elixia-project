/*
	Name			- get_multidepot_load
    Description 	-	get load for mutidepot to calculate indent
    Parameters		-	factoryidparam, multidepotcombination, customerno, daterequired, todaysdate, userid
    Module			-TMS
    Sub-Modules 	- 	Indent Algo 
    Sample Call		-	CALL  - 

    Created by		-	Shrikant Suryawanshi
    Created	on		Nov, 2015
    Change details 	-
    1) 	Updated by	-	Shrikant Suryawanshi
	Updated	on	-	31 Dec 2015
        Reason		-	To Check multidropdepotid != 0 
    2) 
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS get_multidepot_load$$ 
CREATE PROCEDURE get_multidepot_load( 
	 IN factoryidparam INT
	, IN multidepotcombination VARCHAR(50)
    , IN custno INT
    , IN reqddate DATETIME
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
    DECLARE multidropdepotid INT DEFAULT 0;
    
    SELECT 	depotid
    INTO 	multidropdepotid
	FROM	multidepot_mapping
	WHERE	factoryid = factoryidparam
    AND		LTRIM(RTRIM(depotmappingid)) = LTRIM(RTRIM(multidepotcombination))
    AND		customerno = custno
    AND     isdeleted = 0; 
    IF (multidropdepotid IS NOT NULL AND multidropdepotid != 0) THEN
		BEGIN
			INSERT INTO factory_delivery( 
										factoryid
										, skuid
										, depotid
										, date_required
										, netWeight
										, grossWeight
										, customerno
										, created_on
										, updated_on 
										, created_by
										, updated_by
									)
			SELECT 		factoryid
						, sku.skuid
                        , multidropdepotid
						, daterequired
						, (sum(totalWeight) / (netgross))
                        , sum(totalWeight)
                        , lod.customerno
                        , todaysdate
                        , todaysdate
                        , userid
                        , userid
            FROM		leftoverdetails AS lod
            INNER JOIN 	leftover_sku_mapping AS lsm ON lod.leftoverid = lsm.leftoverid
            INNER JOIN	sku ON lsm.skuid = sku.skuid
            WHERE		factoryid = factoryidparam
            AND 		FIND_IN_SET(lod.depotid, multidepotcombination)
            AND			isProcessed = 0
            AND			lod.customerno = custno
            GROUP BY 	daterequired, factoryid, depotid;
            
            /* UPDATE the above entries as processed in SKU left over details*/
            
            UPDATE	leftoverdetails AS lod
            SET 	isProcessed = 1
					, updated_on = todaysdate
                    , updated_by = userid
			WHERE	factoryid = factoryidparam
            AND 	FIND_IN_SET(lod.depotid, multidepotcombination)
            AND		daterequired = reqddate
			AND		customerno = custno;
            
		END;
	END IF;
    
    
END$$
DELIMITER ;
