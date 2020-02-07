DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_bill`$$
CREATE PROCEDURE `delete_bill`(
		 IN billidparam INT
        , IN custno INT
		, IN todaysdate DATETIME
		, IN userid INT
        
)
BEGIN
    update billpayable SET
			isdeleted = 1
	WHERE billid = billidparam 
    AND customerno = custno;
    
END$$
DELIMITER ;
