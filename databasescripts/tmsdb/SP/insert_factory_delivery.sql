DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_factory_delivery`$$
CREATE  PROCEDURE `insert_factory_delivery`( 
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
