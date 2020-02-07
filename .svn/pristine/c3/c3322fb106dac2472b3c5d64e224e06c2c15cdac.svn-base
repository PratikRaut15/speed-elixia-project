/*
    Name		-	team_cron_get_knowledgebase_email
    Description 	-	To get all emails to send for knowledgebase share
    Parameters		-	current datetime format:2015-01-19 14:30
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL team_cron_get_knowledgebase_email(curdatetime);
    Created by		-	Sahil
    Created on		- 	19 Jan, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	19 Jan, 2016
        Reason		-	New.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `team_cron_get_knowledgebase_email`$$
CREATE PROCEDURE `team_cron_get_knowledgebase_email`(
IN curdatetime datetime
)
BEGIN
SELECT *
FROM(SELECT *,
CASE 
   WHEN islater = 1 THEN
     `laterdatetime`
   ELSE
    `createdon` 
   END
   AS readtime   
FROM `knowledgebase_emaillog`) as ke
WHERE DATE_FORMAT(ke.readtime,'%Y-%m-%d %H:%i') = curdatetime AND ke.isdeleted = 0 AND ke.issent =0;
END$$
DELIMITER ;
