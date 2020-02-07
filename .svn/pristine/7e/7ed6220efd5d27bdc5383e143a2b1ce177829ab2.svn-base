-- Insert SQL here.

CREATE TABLE IF NOT EXISTS `discount_specific` (
`dsid` int(11) NOT NULL primary key auto_increment,
  `discount_id` int(11) DEFAULT NULL,
  `clientid` int(11) DEFAULT NULL,
  `locationid` int(11) DEFAULT NULL,
  `branchid` int(11) DEFAULT NULL,
  `customerno` int(11) NOT NULL,
  `entrytime` datetime NOT NULL,
  `addedby` int(11) NOT NULL,
  `updatedtime` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
);

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 15, NOW(), 'ganesh','Discount Specific create table');



