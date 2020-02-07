DELIMITER $$
DROP PROCEDURE IF EXISTS update_sku$$
CREATE PROCEDURE `update_sku`( 
	IN skuidparam INT
	, IN skucode varchar(100)
	, IN sku_description varchar(250)
	, IN typeid varchar(25)
	, IN volume decimal (9,5)
	, IN weight decimal (9,5)
	, IN netgross decimal (5,2)
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE sku
	SET 
		skucode = skucode
		, sku_description = sku_description
		, skutypeid = typeid
		, volume = volume
		, weight = weight
		, netgross = netgross
		, updated_on = todaysdate
        , updated_by = userid
	WHERE skuid = skuidparam;
END$$
DELIMITER ;