DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_leftoverdetails`$$
CREATE PROCEDURE `delete_leftoverdetails`(
        IN leftoveridparam INT
        , IN custno DATE
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    IF(leftoveridparam = '' OR leftoveridparam = 0) THEN
		SET leftoveridparam = NULL;
    END IF;

	UPDATE leftoverdetails 
    SET isdeleted = 1
    WHERE (customerno = custno OR custno IS NULL)
    AND ((leftoverid = leftoveridparam) OR leftoveridparam IS NULL)
    AND   isdeleted = 0;
END$$
DELIMITER ;
