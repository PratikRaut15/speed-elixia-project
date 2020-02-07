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
    IF (multidropdepotid IS NOT NULL ) THEN
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


DELIMITER $$
DROP PROCEDURE IF EXISTS insert_factory_delivery$$
CREATE PROCEDURE insert_factory_delivery( 
	IN factoryid int
	, IN skuidparam int
	, IN depotid int
	, IN date_required date
	, IN weight decimal(7,3)
	, IN custno INT
    , IN todaysdate DATETIME
    , IN userid INT
	, OUT currentfactorydeliveryid INT
)
BEGIN
	DECLARE grosswt decimal(7,3);
    DECLARE tempnetgross decimal(5,2);
    DECLARE skuweight decimal(9,5);

    SELECT  netgross
	INTO 	tempnetgross
    FROM 	sku
    WHERE 	skuid = skuidparam
    AND 	customerno = custno;
    
    SET grosswt = weight * (COALESCE(tempnetgross,0));
    
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
	VALUES ( 
				factoryid 
                , skuidparam 
                , depotid
                , date_required
                , weight
                , grosswt
                , custno
                , todaysdate
                , todaysdate
                , userid
                , userid
			);
	
       
	SET currentfactorydeliveryid = LAST_INSERT_ID();

END$$
DELIMITER ;
