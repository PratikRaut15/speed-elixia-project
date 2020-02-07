-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'420', '2016-10-13 04:31:01', 'Gp','add table amc','0'
);

-- Insert SQL here.


CREATE TABLE IF NOT EXISTS `amc`(
`amcid` int(11) NOT NULL primary key auto_increment,
  `vehicleid` int(11) NOT NULL,
  `agree_start_date` date NOT NULL,
  `agree_end_date` date NOT NULL,
  `total_insured_km` int(11) NOT NULL,
  `insured_month` int(11) NOT NULL,
  `startkm` int(11) NOT NULL,
  `endkm` int(11) NOT NULL,
  `paidamt` float NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `entrytime` datetime NOT NULL,
  `added_by` int(11) NOT NULL
);

ALTER TABLE `amc` CHANGE `paidamt` `paidamt` DECIMAL( 10, 2 ) NOT NULL;

-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 420;
