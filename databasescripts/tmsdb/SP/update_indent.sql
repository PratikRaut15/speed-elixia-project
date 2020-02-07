DELIMITER $$
DROP PROCEDURE IF EXISTS update_indent$$
CREATE PROCEDURE `update_indent`( 
	IN indentidparam int,
	IN transporterid int,
	IN vehicleid int,
	IN proposedindentid int,
	IN indent_sku_mappingid int,
	IN shipmentno varchar(50),
	IN totalweight float(6,2),
	IN totalvolume float(6,2),
	IN isdelivered tinyint(1)
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE 	indent
	SET 	transporterid = transporterid
			,vehicleid = vehicleid
			,proposedindentid = proposedindentid
			,indent_sku_mappingid = indent_sku_mappingid
			,shipmentno = shipmentno
			,totalweight = totalweight
			,totalvolume = totalvolume
			,isdelivered = isdelivered
			,updated_on = todaysdate
			,updated_by = userid
	WHERE 	indentid = indentidparam;

END$$
DELIMITER ;