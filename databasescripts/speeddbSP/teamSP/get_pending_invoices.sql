/*
    Name		-	get_pending_invoices
    Description 	-	get All pending invoices of customer
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_pending_invoices('3');
    Created by		-	Arvind
    Created on		- 	13 September, 2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_pending_invoices`$$
CREATE PROCEDURE `get_pending_invoices`(
        IN customernos INT
)
BEGIN
    IF(customernos = '' OR customernos = '0') THEN
		SET customernos = NULL;
	END IF;

SELECT count(d.device_invoiceno) AS pending_invoices
    FROM `devices` as d
    WHERE d.customerno=customernos AND d.device_invoiceno=''
;     
END$$
DELIMITER ;
