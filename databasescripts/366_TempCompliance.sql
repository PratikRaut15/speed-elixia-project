ALTER TABLE `temp_compliance` ADD `isdeleted` TINYINT(1) NOT NULL DEFAULT '0';

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_temp_compliance`$$
CREATE PROCEDURE `get_temp_compliance`(
	IN vehidparam INT
	,IN custnoparam INT
)
BEGIN
	
	IF(custnoparam = '' OR custnoparam = 0) THEN
		SET custnoparam = NULL;
    END IF;
    IF(vehidparam = '' OR vehidparam = 0) THEN
		SET vehidparam = NULL;
    END IF;
    
    SELECT temp_compliance_id
			, uid
            , vehicleid
            , bc1
            , gc_c1
            , gc_nc1
            , min_1
            , max_1
            , min_range_1
            , max_range_1
            , bc2
            , gc_c2
            , gc_nc2
            , min_2
            , max_2
            , min_range_2
            , max_range_2
            , bc3
            , gc_c3
            , gc_nc3
            , min_3
            , max_3
            , min_range_3
            , max_range_3
            , bc4
            , gc_c4
            , gc_nc4
            , min_4
            , max_4
            , min_range_4
            , max_range_4
            , `timestamp`
            , customerno
            , range_change_timestamp
	FROM 	temp_compliance
    WHERE 	(customerno = custnoparam OR custnoparam IS NULL)
    AND		(vehicleid = vehidparam OR vehidparam IS NULL)
    AND 	isdeleted = 0
    ORDER BY `timestamp`;
    
END$$
DELIMITER ;