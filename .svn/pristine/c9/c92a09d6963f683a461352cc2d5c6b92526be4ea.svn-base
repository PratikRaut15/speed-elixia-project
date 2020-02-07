INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('659', '2018-02-05 14:30:00', 'Yash Kanakia', 'Company Roles', '0');

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
('Field Engineers',8),('Executive Assistant',8),('Human Resource Head',8),
('Business Analyst',8),('Product Head',8),('Senior Software Engineer',1),
('Software Engineer',1),('Marketing Head',8),('Distributors',8),('Quality Assurance',1),
('Software Tester',1),('Miscellaneous',8);



UPDATE  dbpatches
SET     patchdate = '2018-02-05 14:30:00',isapplied = 1
WHERE   patchid = 659;
