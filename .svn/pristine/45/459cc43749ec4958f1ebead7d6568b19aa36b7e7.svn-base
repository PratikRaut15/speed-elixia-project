-- Insert SQL here.

CREATE TABLE IF NOT EXISTS `account_head` (
`uid` int(11) NOT NULL,
  `headid` int(11) NOT NULL,
  `headtype` varchar(50) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
); 


INSERT INTO `account_head` (`uid`, `headid`, `headtype`, `isdeleted`) VALUES
(1, 1, 'Conveyance', 0),
(2, 2, 'Repairs & Maintenance', 0),
(3, 3, 'Food Expenses', 0),
(4, 4, 'Office Expenses', 0),
(5, 5, 'Electrical Expenses', 0),
(6, 6, 'Courier Expenses', 0),
(7, 7, 'Tel. & Mobile Charges', 0),
(8, 8, 'Tours & Travelling', 0),
(9, 9, 'Petrol & Fuel Exp.', 0),
(10, 10, 'Total Expenses', 0),
(11, 11, 'Postage & Telegram', 0),
(12, 12, 'Other Expenses', 0);

ALTER TABLE `account_head`
 ADD PRIMARY KEY (`uid`);

ALTER TABLE `account_head`
MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
 VALUES ( 191, NOW(), 'Ganesh Papde','Voucher Head types');
