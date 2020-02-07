/* tables */
DROP TABLE IF EXISTS `knowledgebase_emaillog`;
CREATE TABLE IF NOT EXISTS `knowledgebase_emaillog` (
`kb_emailid` int(11) NOT NULL,
  `kbsid` int(11) NOT NULL,
  `kb_to` varchar(50) NOT NULL,
  `kb_from` varchar(50) NOT NULL,
  `kb_subject` varchar(250) NOT NULL,
  `kb_message` text NOT NULL,
  `islater` tinyint(4) NOT NULL,
  `laterdatetime` datetime NOT NULL,
  `issent` tinyint(4) NOT NULL,
  `customerno` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
);

ALTER TABLE `knowledgebase_emaillog`
 ADD PRIMARY KEY (`kb_emailid`);

ALTER TABLE `knowledgebase_emaillog`
MODIFY `kb_emailid` int(11) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `knowledgebase_share`;
CREATE TABLE IF NOT EXISTS `knowledgebase_share` (
`kbsid` int(11) NOT NULL,
  `kbs_from` varchar(50) NOT NULL,
  `kbs_subject` varchar(250) NOT NULL,
  `kbs_message` text NOT NULL,
  `moduleid` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
);

ALTER TABLE `knowledgebase_share`
 ADD PRIMARY KEY (`kbsid`);

ALTER TABLE `knowledgebase_share`
MODIFY `kbsid` int(11) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `knowledgebase_emaillog_history`;
CREATE TABLE IF NOT EXISTS `knowledgebase_emaillog_history` (
`kbhist_emailid` int(11) NOT NULL,
`kb_emailid` int(11) NOT NULL,
  `kbsid` int(11) NOT NULL,
  `kb_to` varchar(50) NOT NULL,
  `kb_from` varchar(50) NOT NULL,
  `kb_subject` varchar(250) NOT NULL,
  `kb_message` text NOT NULL,
  `islater` tinyint(4) NOT NULL,
  `laterdatetime` datetime NOT NULL,
  `issent` tinyint(4) NOT NULL,
  `customerno` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
);

ALTER TABLE `knowledgebase_emaillog_history`
 ADD PRIMARY KEY (`kbhist_emailid`);
ALTER TABLE `knowledgebase_emaillog_history`
MODIFY `kbhist_emailid` int(11) NOT NULL AUTO_INCREMENT;

/*Procedure*/
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

DELIMITER $$
DROP PROCEDURE IF EXISTS `team_cron_getsent_knowledgebase_email`$$
CREATE PROCEDURE `team_cron_getsent_knowledgebase_email`(
)
BEGIN
SELECT * FROM knowledgebase_emaillog WHERE issent = 1;
END$$
DELIMITER ;

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES (348, NOW(), 'Sahil','new knowledge share table for team');
