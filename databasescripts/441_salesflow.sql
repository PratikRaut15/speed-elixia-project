
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'441', 'NOW()', 'GP', 'sales flow tables', '0'
);



CREATE TABLE IF NOT EXISTS `sales_stage` (
  `stageid` int(11) NOT NULL primary key auto_increment,
  `stage_name` varchar(30) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `teamid_creator` int(11) NOT NULL
);


CREATE TABLE IF NOT EXISTS `sales_source` (
  `sourceid` int(11) NOT NULL primary key auto_increment,
  `source_name` varchar(30) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `teamid_creator` int(11) NOT NULL
);

CREATE TABLE IF NOT EXISTS `sales_product` (
  `productid` int(11) NOT NULL primary key auto_increment,
  `product_name` varchar(30) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `teamid_creator` int(11) NOT NULL
);


CREATE TABLE IF NOT EXISTS `sales_industry_type` (
  `industryid` int(11) NOT NULL primary key auto_increment,
  `industry_type` varchar(30) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `teamid_creator` int(11) NOT NULL
);

CREATE TABLE IF NOT EXISTS `sales_mode` (
  `modeid` int(11) NOT NULL primary key auto_increment,
  `mode` varchar(30) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `teamid_creator` int(11) NOT NULL
);


CREATE TABLE IF NOT EXISTS `sales_pipeline` (
  `pipelineid` int(11) NOT NULL primary key auto_increment,
  `pipeline_date` date NOT NULL,
  `company_name` varchar(50) NOT NULL,
  `sourceid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `industryid` int(11) NOT NULL,
  `modeid` int(11) NOT NULL,
  `teamid` int(11) NOT NULL,
  `location` varchar(200) NOT NULL,
  `remarks` TEXT NOT NULL,
  `stageid` int(11) NOT NULL,
  `qtnno` varchar(20) NOT NULL,
  `qtndate` date NOT NULL,
  `pono` varchar(20) NOT NULL,
  `podate` date NOT NULL,
  `no_of_devices` int(11) NOT NULL,
  `device_type` varchar(50) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `teamid_creator` int(11) NOT NULL
);


CREATE TABLE IF NOT EXISTS `sales_contact` (
  `contactid` int(11) NOT NULL primary key auto_increment,
  `pipelineid` int(11) NOT NULL,
  `designation` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `email` varchar(20) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `teamid_creator` int(11) NOT NULL
);


CREATE TABLE IF NOT EXISTS `sales_pipeline_history` (
  `pipeline_history_id` int(11) NOT NULL primary key auto_increment,
  `pipelineid` int(11) NOT NULL,
  `pipeline_date` date NOT NULL,
  `company_name` varchar(50) NOT NULL,
  `sourceid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `industryid` int(11) NOT NULL,
  `modeid` int(11) NOT NULL,
  `teamid` int(11) NOT NULL,
  `location` varchar(200) NOT NULL,
  `remarks` TEXT NOT NULL,
  `stageid` int(11) NOT NULL,
  `qtnno` varchar(20) NOT NULL,
  `qtndate` date NOT NULL,
  `pono` varchar(20) NOT NULL,
  `podate` date NOT NULL,
  `no_of_devices` int(11) NOT NULL,
  `device_type` varchar(50) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `teamid_creator` int(11) NOT NULL
);




CREATE TABLE IF NOT EXISTS `sales_reminder` (
  `reminderid` int(11) NOT NULL primary key auto_increment,
  `reminder_datetime` datetime NOT NULL,
  `content` TEXT NOT NULL,
  `pipelineid` int(11) NOT NULL,
  `contactid` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `teamid_creator` int(11) NOT NULL
);


UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 441;
