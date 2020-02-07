-- getCustomerList

DELIMITER $$
DROP PROCEDURE IF EXISTS `getCustomerList`$$
CREATE PROCEDURE `getCustomerList`(
    IN customernoParam TEXT
)
BEGIN

    IF (customernoParam != '') THEN
    
        SELECT  c.customerno
                , c.customername
                , c.customercompany
                , c.customerTypeId
        FROM    customer AS c
        WHERE   c.customercompany <> 'Elixia Tech' 
        AND     FIND_IN_SET(c.customerno,customernoParam)
        ORDER BY c.customerno ASC; 

    END IF;

END$$
DELIMITER ;