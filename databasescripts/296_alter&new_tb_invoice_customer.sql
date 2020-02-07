ALTER TABLE `vehicle`  ADD `invcustid` INT(11) NOT NULL ;


--
-- Table structure for table `invoice_customer`
--

CREATE TABLE IF NOT EXISTS `invoice_customer` (
`invcustid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `invoicename` varchar(21) NOT NULL,
  `address1` varchar(50) NOT NULL,
  `address2` varchar(50) NOT NULL,
  `address3` varchar(50) NOT NULL
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `invoice_customer`
--
ALTER TABLE `invoice_customer`
 ADD PRIMARY KEY (`invcustid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `invoice_customer`
--
ALTER TABLE `invoice_customer`
MODIFY `invcustid` int(11) NOT NULL AUTO_INCREMENT;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (296, NOW(), 'Sahil Gandhi','alter vehicle & new table invoice_customer ');
