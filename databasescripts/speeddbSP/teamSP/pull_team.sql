/*
    Name		-	pull_team
    Description 	-	Pull all teamid and name.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL pull_team()
    Created by		-	Arvind
    Created on		- 	23 Nov, 2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/


DELIMITER $$
DROP PROCEDURE IF EXISTS pull_team$$
CREATE PROCEDURE pull_team()

BEGIN
SELECT teamid,name FROM team;
END$$
DELIMITER ;