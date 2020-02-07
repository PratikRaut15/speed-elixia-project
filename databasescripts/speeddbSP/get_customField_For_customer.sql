/*
    Name		-	get_customField_customer
    Description 	-	get All custom fields for customer
    Parameters		-	
    Module		-	Speed
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_customField_customer(421);
    Created by		-	Arvind
    Created on		- 	10 Feb, 2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/


DELIMITER $$
DROP PROCEDURE IF EXISTS get_customField_customer$$
CREATE PROCEDURE `get_customField_customer`(
  IN customernoParam INT
)
BEGIN

SELECT    	customtype.`name`,customfield.customname
FROM    	customfield
INNER JOIN 	customer ON customer.customerno=customfield.customerno 
INNER JOIN 	customtype ON customtype.id=customfield.custom_id
WHERE  		customfield.usecustom=1 and customer.customerno=customernoParam;

END$$
DELIMITER ;