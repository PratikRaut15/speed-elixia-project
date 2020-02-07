/*
    Name		-	team_cron_archive_knowledgebase_email
    Description 	-	archive all emails that are send for knowledgebase share 
    Parameters		-	emailidparam INT,cnoparam INT
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL team_cron_archive_knowledgebase_email(800,260);
    Created by		-	Sahil
    Created on		- 	19 Jan, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	19 Jan, 2016
        Reason		-	New SP.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `team_cron_archive_knowledgebase_email`$$
CREATE PROCEDURE `team_cron_archive_knowledgebase_email`(
IN emailidparam INT
,IN cnoparam INT 
)
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		ROLLBACK;
	END;
    START TRANSACTION;
    BEGIN
    INSERT INTO knowledgebase_emaillog_history(
    kb_emailid
    ,kbsid
    ,kb_to
    ,kb_from
    ,kb_subject
    ,kb_message
    ,islater
    ,laterdatetime
    ,issent
    ,customerno
    ,createdby
    ,createdon
    ,updatedby
    ,updatedon
    ,isdeleted
    )
    SELECT
		ke.kb_emailid
        ,ke.kbsid
        ,ke.kb_to
        ,ke.kb_from
        ,ke.kb_subject
        ,ke.kb_message
        ,ke.islater
        ,ke.laterdatetime
        ,ke.issent
        ,ke.customerno
        ,ke.createdby
        ,ke.createdon
        ,ke.updatedby
        ,ke.updatedon
        ,ke.isdeleted
    FROM knowledgebase_emaillog ke
    WHERE ke.kb_emailid = emailidparam
    AND ke.customerno = cnoparam
    ;
    call team_delete_knowledgebase_emaillog(cnoparam,emailidparam);
    
    COMMIT;
    END;
END$$
DELIMITER ;
