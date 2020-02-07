

/*
    Name		-	get_low_sms_left_cust
    Description 	-	get Customer who have sms left less than 50
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_low_sms_left_cust();
    Created by		-	Arvind
    Created on		- 	16 September, 2016
    Change details 	-	
    1) 	Updated by	- 	Sanket
	Updated	on	- 	25 OCT, 2016
        Reason		-	Removed Demo (-1) and Closed (-2) customers
    2) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_low_sms_left_cust`$$
CREATE PROCEDURE `get_low_sms_left_cust`()
BEGIN
    SELECT  c.customerno
            ,c.customercompany
            ,c.smsleft
    FROM    customer AS c
    WHERE   c.customercompany <> 'Elixia Tech' 
    AND     c.use_trace = 0 
    AND     c.smsleft < 50 
    AND     c.renewal NOT IN (-1,-2)
    ORDER BY c.customerno ASC;     
END$$
DELIMITER ;
