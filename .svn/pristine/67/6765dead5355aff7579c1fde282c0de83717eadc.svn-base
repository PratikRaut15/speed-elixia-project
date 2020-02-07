INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'459', '2017-02-10 15:20:40', 'Arvind Thakur', 'customfield for customer and reportMaster modified', '0'
);



ALTER TABLE `reportMaster`
ADD COLUMN `is_warehouse` TINYINT(2)  DEFAULT 0 AFTER `reportName`;

UPDATE `reportMaster` SET `is_warehouse`=1 WHERE `reportId` IN (2,3,4,10,11);

DELIMITER $$
DROP PROCEDURE IF EXISTS get_customField_customer$$
CREATE PROCEDURE `get_customField_customer`(
  IN customernoParam INT
)
BEGIN

SELECT    	customtype.`name`,customfield.customname
FROM    	customfield
INNER JOIN 	customer ON customer.customerno=customfield.customerno 
INNER JOIN 	customtype ON customtype.id=customfield.custom_id
WHERE  		customfield.usecustom=1 and customer.customerno=customernoParam;

END$$
DELIMITER ;

UPDATE  dbpatches
SET     patchdate = NOW()
    , isapplied =1
WHERE   patchid = 459;