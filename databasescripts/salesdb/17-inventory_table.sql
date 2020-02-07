
CREATE TABLE IF NOT EXISTS `inventory` (
`invid` int(11) NOT NULL,
  `distributorid` int(11) NOT NULL,
  `skuid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `stockdate` datetime NOT NULL,
  `is_asm` tinyint(5) NOT NULL DEFAULT '0',
  `customerno` int(11) NOT NULL,
  `entrydate` datetime NOT NULL,
  `addedby` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_time` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
);

ALTER TABLE `inventory` ADD PRIMARY KEY (`invid`);

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 17, NOW(), 'Ganesh','Inventory table add ');
