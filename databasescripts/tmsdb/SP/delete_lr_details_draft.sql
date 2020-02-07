DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_lr_details_draft`$$
CREATE  PROCEDURE `delete_lr_details_draft`(
		 IN lridparam varchar(50)
        , IN custno INT
		, IN todaysdate DATETIME
		, IN userid INT
        
)
BEGIN
    update lrDetails_draft SET
			isdeleted = 1
	WHERE FIND_IN_SET(lrid,lridparam)
    AND customerno = custno;
    
END$$
DELIMITER ;
