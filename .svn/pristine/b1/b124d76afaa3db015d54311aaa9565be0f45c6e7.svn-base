/*
    Name		-	sim_of_teamid
    Description 	-	details of simcard assigneed to team member.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL sim_of_teamid(5);
    Created by		-	Arvind
    Created on		- 	23 Nov, 2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS sim_of_teamid$$
CREATE PROCEDURE sim_of_teamid(
	IN teamidparam INT
	)
BEGIN
SELECT simcard.id as simid, simcard.simcardno FROM simcard 
INNER JOIN trans_status ON trans_status.id = simcard.trans_statusid 
WHERE trans_statusid IN (19,21) AND simcard.teamid=teamidparam;

END$$
DELIMITER ;