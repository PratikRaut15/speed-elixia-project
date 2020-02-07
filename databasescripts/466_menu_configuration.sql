
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'466', '2017-02-21 15:20:40', 'Ganesh', 'Menu configuration', '0'
);


CREATE TABLE `usermenu_mapping` (
  `umid` int(11) NOT NULL AUTO_INCREMENT,
  `menuid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `view_permission` tinyint(1) NOT NULL DEFAULT '0',
  `edit_permission` tinyint(1) NOT NULL DEFAULT '0',
  `delete_permission` tinyint(1) NOT NULL DEFAULT '0',
  `customerno` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `isactive` tinyint(2) NOT NULL DEFAULT '0',
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`umid`)
);


DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_default_menu`$$
CREATE PROCEDURE `insert_default_menu`(
IN `moduleidparam` INT, 
IN `customernoparam` INT, 
IN `todaysdate` DATETIME, 
IN `useridparam` INT, 
IN `createdbyparam` INT 
)
BEGIN

INSERT INTO usermenu_mapping (menuid,userid,customerno,created_by,created_on)
SELECT menuid,useridparam,customernoparam,createdbyparam,todaysdate FROM menu_master where moduleid=moduleidparam AND isdeleted=0 OR customerno=customernoparam ;

END$$
DELIMITER ;


UPDATE  dbpatches
SET     patchdate = NOW()
    , isapplied =1
WHERE   patchid = 466;
