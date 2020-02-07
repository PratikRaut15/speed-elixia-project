DELIMITER $$
DROP PROCEDURE IF EXISTS `pullCoordinator`$$
CREATE PROCEDURE `pullCoordinator`(
     IN customernoParam INT(11)
     )
BEGIN

    SELECT  cpdetailid
            , person_name 
    FROM    contactperson_details 
    WHERE   customerno = customernoParam 
    AND     typeid = 3;

END$$
DELIMITER ;  


-- call pullCoordinator('3');