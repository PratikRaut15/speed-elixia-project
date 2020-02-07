INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'663', '2018-02-11 12:30:00', 'Yash Kanakia','ModifyCustomer Page', '0');



DELIMITER $$
DROP procedure IF EXISTS `get_customer_additional_details`$$
CREATE PROCEDURE `get_customer_additional_details`(

	IN custnoParam INT(11)

    )
BEGIN 

	SELECT *,cm.person_typeid,cm.person_type FROM  contactperson_details cd

	INNER JOIN contactperson_type_master as cm ON cd.typeid = cm.person_typeid

	WHERE customerno = custnoParam AND isdeleted = 0;
END$$
DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `get_industry` $$
CREATE PROCEDURE `get_industry`(
)
BEGIN
SELECT  industryid,industry_type from sales_industry_type where isdeleted=0;
END$$
DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `get_sales_person`$$
CREATE PROCEDURE `get_sales_person`(
)
BEGIN
SELECT  teamid,name from team where role='Sales';
END$$
DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `get_contact_typemaster`$$
CREATE PROCEDURE `get_contact_typemaster`()
BEGIN 

SELECT * FROM contactperson_type_master;

           
END$$

DELIMITER ;

UPDATE  dbpatches
SET     patchdate = '2018-02-11 12:30:00'
        ,isapplied =1
WHERE   patchid = 663;


