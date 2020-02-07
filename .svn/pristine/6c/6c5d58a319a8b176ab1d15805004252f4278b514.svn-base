/*
    Name		-	pullTicketType
    Description 	-	Pull all ticket type.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL pullTicketType()
    Created by		-	Arvind
    Created on		- 	06 April, 2017
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/


DELIMITER $$
DROP PROCEDURE IF EXISTS pullemail$$
CREATE PROCEDURE pullemail(
    IN customernoParam INT(11)
)

BEGIN

    SELECT  `eid`
            ,`email_id` 
    FROM    `report_email_list`
    WHERE   `customerno` IN (customernoParam,0);

END$$
DELIMITER ;