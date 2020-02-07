/*
    Name		-	team_cron_update_knowledgebase_email
    Description 	-	update all emails send for knowledgebase share to 1 for issent
    Parameters		-	emailidparam INT,issentparam tinyint
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL team_cron_update_knowledgebase_email(800,1);
    Created by		-	Sahil
    Created on		- 	19 Jan, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	19 Jan, 2016
        Reason		-	New SP.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `team_cron_update_knowledgebase_email`$$
CREATE PROCEDURE `team_cron_update_knowledgebase_email`(
IN emailidparam INT
,IN issentparam tinyint

)
BEGIN

UPDATE knowledgebase_emaillog SET issent = issentparam 
WHERE kb_emailid = emailidparam;
END$$
DELIMITER ;
