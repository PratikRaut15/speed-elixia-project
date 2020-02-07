/*
    Name		-	team_cron_getsent_knowledgebase_email
    Description 	-	Get  all sent emails from knowledgebase_emaillog table
    Parameters		-	No
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL team_cron_getsent_knowledgebase_email();
    Created by		-	Sahil
    Created on		- 	19 Jan, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	19 Jan, 2016
        Reason		-	New SP.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `team_cron_getsent_knowledgebase_email`$$
CREATE PROCEDURE `team_cron_getsent_knowledgebase_email`(
)
BEGIN
SELECT * FROM knowledgebase_emaillog WHERE issent = 1;
END$$
DELIMITER ;
