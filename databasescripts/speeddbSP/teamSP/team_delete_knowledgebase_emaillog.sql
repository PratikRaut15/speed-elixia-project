/*
    Name		-	team_delete_knowledgebase_emaillog
    Description 	-	Delete all emails that are send from knowledgebase_emaillog table
    Parameters		-	emailidparam INT,custnoparam INT
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL team_delete_knowledgebase_emaillog(260,800);
    Created by		-	Sahil
    Created on		- 	19 Jan, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	19 Jan, 2016
        Reason		-	New SP.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `team_delete_knowledgebase_emaillog`$$
CREATE PROCEDURE `team_delete_knowledgebase_emaillog`(
IN custnoparam INT
,IN emailidparam INT 
)
BEGIN
DELETE FROM knowledgebase_emaillog 
    WHERE customerno = custnoparam
    AND kb_emailid = emailidparam
    ;
END$$
DELIMITER ;
