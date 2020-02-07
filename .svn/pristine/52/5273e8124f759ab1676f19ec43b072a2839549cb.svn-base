DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_bill_draft`$$
CREATE PROCEDURE `delete_bill_draft`(
		 IN billidparam INT
        , IN custno INT
		, IN todaysdate DATETIME
		, IN userid INT
        
)
BEGIN
    update billpayable_draft SET
			isdeleted = 1
	WHERE billid = billidparam 
    AND customerno = custno;
    
END$$
DELIMITER ;
