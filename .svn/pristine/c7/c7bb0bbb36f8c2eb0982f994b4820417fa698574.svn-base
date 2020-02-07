INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('11', '2019-01-14 12:00:00', 'Yash Kanakia', 'Product Master Changes', '0');

ALTER TABLE elixiaOneProductMaster
ADD COLUMN customerCreate_path VARCHAR(200);

ALTER TABLE customer
ADD COLUMN use_books tinyInt,
ADD COLUMN use_controlTower tinyInt,
ADD COLUMN use_crm tinyInt;

DELIMITER $$
DROP procedure IF EXISTS `fetch_product_url`$$
CREATE PROCEDURE `fetch_product_url`()
BEGIN

SELECT DISTINCT customerCreate_path from elixiaOneProductMaster
WHERE customerCreate_path IS NOT NULL ;

END$$

DELIMITER ;


DROP TABLE IF EXISTS `elixiaOneProductMaster`;
CREATE TABLE `elixiaOneProductMaster` (
  `prodId` tinyint(4) NOT NULL,
  `prodName` varchar(25) NOT NULL,
  `prodPrice` int(11) NOT NULL,
  `launchPrice` int(11) NOT NULL,
  `customerCreate_path` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`prodId`)
);


INSERT INTO `elixiaOneProductMaster`
VALUES (1,'Elixia Speed',300,300,NULL),
(2,'Elixia Trace',300,300,NULL),
(3,'Elixia Fleet',300,200,'http://fleet.elixiatech.com/modules/api/fleet/api.php'),
(4,'Elixia Docs',200,100,NULL),
(5,'Elixia Sales',450,300,NULL),
(6,'Elixia Books',35000,25000,'http://books.elixiatech.com/api/insert_customer'),
(7,'Elixia Stock',7500,5000,NULL),
(8,'Elixia Trip',25000,15000,NULL),
(9,'Elixia Client',0,0,NULL),
(10,'Elixia Driver',0,0,NULL),
(11,'Elixia ERP',0,0,'http://erp.elixiatech.com/api/insert_customer'),
(12,'Elixia Monitor',0,0,NULL),
(13,'Elixia Radar',0,0,NULL),
(14,'Elixia Control Tower',0,0,'http://controltower.elixiatech.com/api/insert_customer'),
(15,'Elixia CRM',0,0,NULL);




UPDATE  dbpatches
SET     isapplied = 1
WHERE   patchid = 11;

