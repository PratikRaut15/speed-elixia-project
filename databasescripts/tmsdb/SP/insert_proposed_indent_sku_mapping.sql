DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_proposed_indent_sku_mapping`$$
CREATE PROCEDURE `insert_proposed_indent_sku_mapping`( 
	IN proposedindentid int
	, IN skuid int 
    , IN no_of_units INT
	, IN weight decimal(11,3)
	, IN volume decimal(11,3)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    
)
BEGIN
		INSERT INTO `proposed_indent_sku_mapping`
		(
			`proposedindentid`
			,`skuid`
			,`no_of_units`
			,`weight`
			,`volume`
			,`customerno`
			,`created_on`
			,`updated_on`
			,`created_by`
			,`updated_by`
		)
		VALUES
		(
				proposedindentid
				, skuid
                , no_of_units
                , weight
				, volume
                , customerno
                , todaysdate
                , todaysdate
                , userid
                , userid
			);

	

END$$
DELIMITER ;
