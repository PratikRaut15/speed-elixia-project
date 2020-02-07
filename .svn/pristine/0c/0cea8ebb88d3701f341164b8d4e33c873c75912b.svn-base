
/*
    Name		-	get_customer_not_allotted_crm
    Description 	-	get Customer Not Alloted CRM Details
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_customer_not_allot_crm();
    Created by		-	Arvind
    Created on		- 	12 September, 2016
    Change details 	-	
    1) 	Updated by	- 	Sanket
	Updated	on	- 	25 OCT, 2016
        Reason		-	Removed Demo (-1) and Closed (-2) customers
    2) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_customer_not_allot_crm`$$
CREATE PROCEDURE `get_customer_not_allot_crm`()
BEGIN
   
    SELECT  c.customerno
            ,c.customername 
            ,c.customercompany
    FROM    `customer` AS c
    WHERE   c.use_trace=0 
    AND     c.renewal NOT IN (-1,-2) 
    AND     (c.rel_manager IS NULL OR c.rel_manager = '' OR c.rel_manager=0);     

END$$
DELIMITER ;

