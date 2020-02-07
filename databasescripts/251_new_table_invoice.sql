-- Insert SQL here.
-- Table structure for table `invoice`
--

CREATE TABLE IF NOT EXISTS `invoice` (
`invoiceid` int(11) NOT NULL,
  `invoiceno` varchar(40) NOT NULL,
  `customerno` int(11) NOT NULL,
  `clientname` varchar(40) NOT NULL,
  `inv_date` date NOT NULL,
  `inv_amt` varchar(20) NOT NULL,
  `status` varchar(40) NOT NULL,
  `pending_amt` varchar(20) NOT NULL,
  `tax` varchar(40) NOT NULL,
  `tax_amt` varchar(20) NOT NULL,
  `pay_mode` varchar(40) NOT NULL,
  `paid_amt` varchar(20) NOT NULL,
  `paymentdate` date NOT NULL,
  `tds_amt` varchar(20) NOT NULL,
  `unpaid_amt` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
 ADD PRIMARY KEY (`invoiceid`);


--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
MODIFY `invoiceid` int(11) NOT NULL AUTO_INCREMENT;

--
-- Table structure for table `invoice_payment`
--

CREATE TABLE IF NOT EXISTS `invoice_payment` (
`payment_id` int(11) NOT NULL,
  `invoiceid` int(11) NOT NULL,
  `payment` varchar(40) NOT NULL,
  `chequeno` varchar(40) NOT NULL,
  `bank_name` varchar(40) NOT NULL,
  `branch` varchar(40) NOT NULL,
  `payment_amt` varchar(20) NOT NULL,
  `payment_date` date NOT NULL,
  `tdsamt` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Indexes for table `invoice_payment`
--
ALTER TABLE `invoice_payment`
 ADD PRIMARY KEY (`payment_id`);


--
-- AUTO_INCREMENT for table `invoice_payment`
--
ALTER TABLE `invoice_payment`
MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 251, NOW(), 'Sahil','new table invoice & invoice payment');
