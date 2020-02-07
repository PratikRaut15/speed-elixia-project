
CREATE TABLE IF NOT EXISTS `master_payment` (
`pid` int(11) NOT NULL,
  `orderid` varchar(11) NOT NULL,
  `type` varchar(11) NOT NULL,
  `amount` varchar(11) NOT NULL,
  `chequeno` varchar(11) NOT NULL,
  `accountno` varchar(25) NOT NULL,
  `branch` varchar(150) NOT NULL,
  `reason` varchar(150) NOT NULL,
  `paymentby` int(4) NOT NULL,
  `pending_amt` varchar(11) NOT NULL,
  `paymentdate` datetime DEFAULT NULL
);

ALTER TABLE `master_payment`
 ADD PRIMARY KEY (`pid`);

ALTER TABLE `master_payment`
MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 23, NOW(), 'Shrikanth Suryawanshi','payment master');


