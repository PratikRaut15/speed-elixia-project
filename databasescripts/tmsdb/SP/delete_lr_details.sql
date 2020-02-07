DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_lr_details`$$
CREATE PROCEDURE `delete_lr_details`(
		 IN lridparam varchar(50)
        , IN custno INT
		, IN todaysdate DATETIME
		, IN userid INT
        
)
BEGIN
    update lrDetails SET
			isdeleted = 1
	WHERE FIND_IN_SET(lrid,lridparam)
    AND customerno = custno;
    
END$$
DELIMITER ;
