/*
    Name		-	get_contact_person_details
    Description 	-	get Contact Person Details
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
DROP PROCEDURE IF EXISTS `get_contact_person`$$
CREATE PROCEDURE `get_contact_person`(
        IN customernos INT
)
BEGIN
    IF(customernos = '' OR customernos = '0') THEN
		SET customernos = NULL;
	END IF;
SELECT cp.typeid
        ,cp.person_name
        ,cp.cp_email1
        ,cp.cp_email2
        ,cp.cp_phone1
        ,cp.cp_phone2 
    FROM contactperson_details as cp
LEFT JOIN customer on customer.customerno=cp.customerno
WHERE customer.customerno=customernos AND cp.isdeleted=0 AND (cp.person_name='' OR cp.cp_email1='' OR cp.cp_email2='' OR cp.cp_phone1='' OR cp.cp_phone2='') 

ORDER BY cp.typeid
;     
END$$
DELIMITER ;
