INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('12', '2019-01-25 19:30:00', 'Yash Kanakia','Company Role Changes', '0');


DELIMITER $$
DROP procedure IF EXISTS `get_company_role`$$
CREATE PROCEDURE `get_company_role`(
IN deptIdParam VARCHAR(100))
BEGIN

  SELECT c_roleId,companyRole from company_role
  WHERE department_id = deptIdParam;

END$$

DELIMITER ;

ALTER TABLE team
ADD COLUMN company_roleId INT;
ALTER TABLE team CHANGE COLUMN company_roleId company_roleId INT AFTER role;



DROP TABLE IF EXISTS `company_role`;
CREATE TABLE `company_role`
(c_roleId INT PRIMARY KEY AUTO_INCREMENT,
companyRole VARCHAR(100),
department_id INT);


INSERT INTO company_role
(`companyRole`,`department_id`)
VALUES('Managing Director',7),
('CEO',7),('CTO',7),('Support Head',5),('CRM',5),('Sales Director',4),('Sales Head',4),
('National Sales Head',4),('Regional Sales Head',4),('Accounts Head',3),
('Accounts Assistant',3),('Operations Head',2),('Backend Operations',2),
('Field Engineers',8),('Executive Admin',8),('Human Resource Head',8),
('Business Analyst',8),('Product Head',8),('Senior Software Engineer',1),
('Software Engineer',1),('Marketing Head',8),('Distributors',8);

DELIMITER $$
DROP procedure IF EXISTS `get_department`$$
CREATE PROCEDURE `get_department`()
BEGIN

  SELECT department_id,department from department;

END$$

DELIMITER ;

UPDATE  dbpatches
SET     patchdate = '2019-01-25 19:30:00'
        ,isapplied =1
WHERE   patchid = 12;
