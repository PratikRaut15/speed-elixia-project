DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_sku`$$
CREATE  PROCEDURE `insert_sku`( 
	IN skucode varchar(100)
	, IN sku_description varchar(250)
	, IN type varchar(25)
	, IN volume decimal (9,5)
	, IN weight decimal (9,5)
	, IN netgross decimal(5,2)
	, IN customerno INT
	, IN todaysdate DATETIME
    , IN userid INT
    , OUT currentskuid INT
)
BEGIN
	INSERT INTO sku 
				(
					skucode
					, sku_description
					, skutypeid
					, volume
					, weight
					, netgross
					, customerno
					, created_on
					, updated_on
                    , created_by
                    , updated_by
				) 
	VALUES 		(
					skucode
					, sku_description
					, type
					, volume
					, weight
					, netgross
					, customerno
					, todaysdate
					, todaysdate
                    , userid
                    , userid
				);

        
	SET currentskuid = LAST_INSERT_ID();
END$$
DELIMITER ;
