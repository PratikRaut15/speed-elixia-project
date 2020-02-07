/*
    Name		-	pullTicketPriority
    Description 	-	Pull all ticket status.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL pullTicketPriority()
    Created by		-	Arvind
    Created on		- 	06 April, 2017
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/


DELIMITER $$
DROP PROCEDURE IF EXISTS pullTicketPriority$$
CREATE PROCEDURE pullTicketPriority()

BEGIN

    SELECT  prid
            ,priority 
    FROM    sp_priority
    WHERE   isdeleted = 0;

END$$
DELIMITER ;