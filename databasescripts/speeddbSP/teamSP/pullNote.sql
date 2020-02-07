/*
    Name		-	pullNote
    Description 	-	Pull all note for ticket.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL pullNote(322);
    Created by		-	Arvind
    Created on		- 	06 April, 2017
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/


DELIMITER $$
DROP PROCEDURE IF EXISTS pullNote$$
CREATE PROCEDURE pullNote(
    IN ticketidParam INT(11)
)

BEGIN

    SELECT  sn.note
            , team.`name`
            , `user`.realname
            , sn.create_on_date
    FROM    sp_note sn 
    LEFT JOIN team ON team.teamid = sn.create_by AND sn.is_customer = 0
    LEFT JOIN `user` ON `user`.userid = sn.create_by AND sn.is_customer = 1
    WHERE   sn.ticketid = ticketidParam 
    ORDER BY sn.noteid DESC;

END$$
DELIMITER ;