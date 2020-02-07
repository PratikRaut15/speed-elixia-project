/*
    Name		-	get_customer_not_allotted_crm
    Description 	-	get Customer Not Alloted CRM Details
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
DROP PROCEDURE IF EXISTS `get_customer_not_allot_crm`$$
CREATE PROCEDURE `get_customer_not_allot_crm`()
BEGIN
   
SELECT c.customerno
    ,c.customername 
    FROM `customer` as c
WHERE c.use_trace=0 AND (c.rel_manager IS NULL OR c.rel_manager = '' OR c.rel_manager=0) 
;     
END$$
DELIMITER ;

