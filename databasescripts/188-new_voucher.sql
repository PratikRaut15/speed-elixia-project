-- Insert SQL here.

CREATE TABLE IF NOT EXISTS `voucher` (
`uid` int(11) NOT NULL,
  `voucherid` int(11) NOT NULL,
  `claimant` int(11) NOT NULL,
  `claimdate` date NOT NULL,
  `headid` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `remarks` varchar(50) NOT NULL,
  `ispaid` tinyint(1) NOT NULL DEFAULT '0'
);

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 188, NOW(), 'Ganesh Papde','petty cash- voucher');
