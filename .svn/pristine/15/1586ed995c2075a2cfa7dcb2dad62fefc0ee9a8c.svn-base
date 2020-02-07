CREATE TABLE IF NOT EXISTS `invoice_customer` (
`invcustid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `invoicename` varchar(21) NOT NULL,
  `address1` varchar(50) NOT NULL,
  `address2` varchar(50) NOT NULL,
  `address3` varchar(50) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
);

ALTER TABLE `invoice_customer`
 ADD PRIMARY KEY (`invcustid`);

ALTER TABLE `invoice_customer`
MODIFY `invcustid` int(11) NOT NULL AUTO_INCREMENT;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (360, NOW(), 'Sahil','new table for multiple invoice address');

