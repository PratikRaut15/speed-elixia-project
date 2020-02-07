INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('526', '2017-07-28 11:19:00','Ganesh Papde','Default role insert', '0');

DELIMITER $$
DROP procedure IF EXISTS `insert_roledefault_menu`$$
CREATE PROCEDURE `insert_roledefault_menu`(
IN `moduleidparam` INT, 
IN `customernoparam` INT, 
IN `todaysdate` DATETIME, 
IN `roleidparam` INT, 
IN `createdbyparam` INT 
)
BEGIN

INSERT INTO role_menumapping (menuid,roleid,customerno,created_by,created_on)
SELECT menuid,roleidparam,customernoparam,createdbyparam,todaysdate FROM menu_master where moduleid=moduleidparam AND isdeleted=0 OR customerno=customernoparam ;

END$$
DELIMITER ;

UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 526;
