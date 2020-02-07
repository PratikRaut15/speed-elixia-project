/*
    Name		-	unit_of_teamid
    Description 	-	details of unit assigneed to team member.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL unit_of_teamid(5);
    Created by		-	Arvind
    Created on		- 	23 Nov, 2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS unit_of_teamid$$
CREATE PROCEDURE unit_of_teamid(
	IN teamidparam INT
	)
BEGIN
SELECT unit.unitno, unit.uid FROM unit 
    INNER JOIN trans_status ON trans_status.id = unit.trans_statusid 
    WHERE trans_statusid IN (18,20) AND unit.teamid=teamidparam;

END$$
DELIMITER ;