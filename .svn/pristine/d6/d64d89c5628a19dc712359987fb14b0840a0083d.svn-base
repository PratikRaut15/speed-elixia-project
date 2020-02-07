

CREATE TABLE IF NOT EXISTS `master_orders_dummy` (
`id` int(11) NOT NULL,
  `order_id` varchar(25) NOT NULL,
  `customerno` int(11) NOT NULL,
  `clientid` int(11) DEFAULT NULL,
  `vendorno` int(11) DEFAULT NULL,
  `sales_type` varchar(35) NOT NULL,
  `ref_number` varchar(35) NOT NULL,
  `email` varchar(50) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `currency` varchar(15) NOT NULL,
  `financial_status` varchar(25) NOT NULL,
  `item_count` varchar(11) NOT NULL,
  `sub_total` varchar(11) NOT NULL,
  `taxes_total` varchar(11) NOT NULL,
  `discount_total` varchar(11) NOT NULL,
  `total` varchar(11) NOT NULL,
  `status` varchar(25) NOT NULL,
  `production_details` text NOT NULL,
  `quantity` varchar(25) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `delivery_date` date NOT NULL,
  `slot` int(11) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `longi` varchar(20) NOT NULL,
  `delivery_lat` varchar(20) NOT NULL,
  `delivery_long` varchar(20) NOT NULL,
  `delivery_time` datetime NOT NULL,
  `accuracy` varchar(20) NOT NULL,
  `fenceid` varchar(20) NOT NULL,
  `areaid` int(11) DEFAULT NULL,
  `total_amount` varchar(25) NOT NULL,
  `reedeem_limit` varchar(25) NOT NULL,
  `iscanceled` tinyint(1) NOT NULL,
  `flag` tinyint(1) NOT NULL,
  `deliveryboyid` int(11) DEFAULT NULL,
  `is_delivered` tinyint(1) DEFAULT '0',
  `reasonid` int(4) DEFAULT NULL,
  `is_sequence` tinyint(1) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=181507 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `master_orders_dummy`
--
ALTER TABLE `master_orders_dummy`
 ADD PRIMARY KEY (`id`), ADD KEY `delivery_date` (`delivery_date`), ADD KEY `order_id` (`order_id`);

--
-- AUTO_INCREMENT for dumped tables
--

