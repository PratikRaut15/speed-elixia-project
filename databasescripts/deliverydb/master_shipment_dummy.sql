--
-- Table structure for table `master_shipment`
--

CREATE TABLE IF NOT EXISTS `master_shipment_dummy` (
`shipmentid` int(11) NOT NULL,
  `orderid` int(11) NOT NULL,
  `order_id` varchar(25) NOT NULL,
  `provider` varchar(50) NOT NULL,
  `tracking_number` varchar(20) NOT NULL,
  `transporter_name` varchar(50) NOT NULL,
  `lr_no` varchar(25) NOT NULL,
  `lr_date` datetime NOT NULL,
  `shipping_status` varchar(25) NOT NULL,
  `cancel_reason` varchar(11) NOT NULL,
  `eta` datetime NOT NULL,
  `service` varchar(25) NOT NULL,
  `price` varchar(11) NOT NULL,
  `return_cod_lebel` varchar(50) NOT NULL,
  `shipping_lebel` varchar(50) NOT NULL,
  `route` varchar(50) NOT NULL,
  `flag` tinyint(1) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=181388 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `master_shipment_dummy`
--
ALTER TABLE `master_shipment_dummy`
 ADD PRIMARY KEY (`shipmentid`), ADD KEY `orderid` (`orderid`);

--
-- AUTO_INCREMENT for dumped tables
--

