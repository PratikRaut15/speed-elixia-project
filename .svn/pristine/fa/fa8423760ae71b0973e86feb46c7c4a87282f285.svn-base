-- Insert SQL here.

CREATE TABLE IF NOT EXISTS `voucher_payment` (
`uid` int(11) NOT NULL,
  `voucher_id` int(11) NOT NULL,
  `voucher_amount` int(11) NOT NULL,
  `pay_amount` int(11) NOT NULL,
  `given_by` varchar(11) NOT NULL,
  `voucher_date` date NOT NULL,
  `payment_date` date NOT NULL,
  `remarks` varchar(50) NOT NULL,
  `done_by` int(11) NOT NULL
);

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 187, NOW(), 'Ganesh Papde','Petty cash');
