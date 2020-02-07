/*
    Name		-	get_customer
    Description 	-	get Customer Details
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_all_customer();
    Created by		-	Arvind
    Created on		- 	12 September, 2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_all_customer`$$
CREATE PROCEDURE `get_all_customer`()
BEGIN
    
SELECT 	c.customerno
        ,c.customername
        ,c.customercompany
        ,c.customerTypeId
        
FROM customer AS c
WHERE c.customercompany <> 'Elixia Tech' AND c.use_trace=0
ORDER BY c.customerno ASC
;     
END$$
DELIMITER ;

