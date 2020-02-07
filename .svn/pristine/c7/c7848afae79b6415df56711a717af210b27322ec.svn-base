INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'564', '2018-05-29 11:00:00', 'Arvind Thakur', 'Checkpoint Type', '0'
);

DROP TABLE IF EXISTS `checkpoint_type`;
CREATE TABLE checkpoint_type (
  `ctid` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `customerno` int(11) NOT NULL DEFAULT 0,
  `createdby` int(11) NOT NULL,
  `createdon` datetime DEFAULT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `checkpoint`
ADD COLUMN `chktype` INT(11) DEFAULT 0 AFTER `cname`;


INSERT INTO checkpoint_type(`name`) 
VALUES('Petrol Pump'),
('Distributor'),
('Warehouse'),
('Plant'),
('Vendor'),
('Stores'),
('Distrubution Centre');


UPDATE dbpatches SET isapplied=1 WHERE patchid = 564;