
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'424', '2016-10-27 13:11:01', 'Ganesh Papde', 'vendor and po table', '0'
);


CREATE TABLE IF NOT EXISTS `team_vendor` (
  `vendorid` int(11) NOT NULL primary key auto_increment,
  `vendor_name` varchar(200) NOT NULL,
  `vendor_type` varchar(200) NOT NULL,
  `vendor_phone` varchar(20) NOT NULL,
  `vendor_email` varchar(100) NOT NULL,
  `vendor_address` varchar(250) NOT NULL,
  `servicetax_no` varchar(200) NOT NULL,
  `panno` varchar(200) NOT NULL,
  `cstno` varchar(200) NOT NULL,
  `vatno` varchar(200) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
);



CREATE TABLE IF NOT EXISTS `vendor_po` (
  `poid` int(11) NOT NULL primary key auto_increment,
  `vendorid` int(11) NOT NULL,
  `podate` date NOT NULL,
  `item` varchar(200) NOT NULL,
  `unitprice` decimal(10,2) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `totalamount` decimal(10,2) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
);

CREATE TABLE IF NOT EXISTS `vendor_item` (
`vitemid` int(11) NOT NULL primary key auto_increment,
  `poid` int(11) NOT NULL,
  `vendorid` int(11) NOT NULL,
  `item` varchar(200) NOT NULL,
  `unitprice` decimal(10,2) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
);


UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 424;

