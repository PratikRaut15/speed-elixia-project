-- --------------------------------------------------------

--
-- Table structure for table `asset`
--

CREATE TABLE IF NOT EXISTS `asset` (
`assetid` int(11) NOT NULL,
  `assetname` varchar(80) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `asset`
--

INSERT INTO `asset` (`assetid`, `assetname`) VALUES
(1, 'BOLERO LX/SPORTZ BSII'),
(2, 'BOLERO SLE 2WD 7 SEATER AC & PS BS3'),
(3, 'BOLERO SLE 2WD 7 SEATER AC & PS BS4'),
(4, 'Bolero SLE 2WD 7str BS3'),
(5, 'BOLERO SLE MDI TC 2WD 7 STR AC RP BS2'),
(6, 'BOLERO ZLX 2WD 7SEATER AC & PS BS3'),
(7, 'MAHINDRA BALERO LX-2WD7STR BS3 MISTSilver COLOUR'),
(8, 'MAHINDRA BOLERO'),
(9, 'MAHINDRA BOLERO 2WD 7SEATER'),
(10, 'MAHINDRA BOLERO 2WD 7STR BS3'),
(11, 'MAHINDRA BOLERO BS3'),
(12, 'MAHINDRA BOLERO BS4'),
(13, 'MAHINDRA BOLERO DI 2680 MMWB'),
(14, 'MAHINDRA BOLERO DI BSIII'),
(15, 'MAHINDRA BOLERO LX MDITC NGT 2WD 7STR BS2 ACRP'),
(16, 'MAHINDRA BOLERO LX MDITC NGT 2WD 7STR BS3 ACRP'),
(17, 'MAHINDRA BOLERO LX MDITC NGT520 2WD 7STR BS3'),
(18, 'MAHINDRA BOLERO MDITC NGT 2WD 7STR BS3 ACRP'),
(19, 'MAHINDRA BOLERO SLE'),
(20, 'MAHINDRA BOLERO SLE 2WD 7 SEATER'),
(21, 'MAHINDRA BOLERO SLE BS3'),
(22, 'MAHINDRA BOLERO SLX MDI-TC 2WD NGT BS3 7STR'),
(23, 'MAHINDRA BOLERO SPORTZ'),
(24, 'MAHINDRA BOLERO-LX'),
(25, 'MAHINDRA BORLERO SLE'),
(26, 'MAHINDRA SCORPIO'),
(27, 'MAHINDRA SCORPIO BS4'),
(28, 'MAHINDRA SCORPIO SC-RF HWK BS3 2WD SLE T&B 7SF RHD DMDWHT'),
(29, 'MAHINDRA SCORPIO SLE 2.2 HWK 2WD'),
(30, 'MAHINDRA SCORPIO SLE 2.2HAWK 2WD 7STR BSIV DIAMOND WHITE'),
(31, 'MAHINDRA SCORPIO SLE 2.2Hawk BS4 2WD 7SF'),
(32, 'SCORPIO SLE BS3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `asset`
--
ALTER TABLE `asset`
 ADD PRIMARY KEY (`assetid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `asset`
--
ALTER TABLE `asset`
MODIFY `assetid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;


--
-- Table structure for table `vehiclevendor`
--

CREATE TABLE IF NOT EXISTS `vehiclevendor` (
`vehiclevendor_id` int(11) NOT NULL,
  `vendor_name` varchar(51) NOT NULL
) 

--
-- Dumping data for table `vehiclevendor`
--

INSERT INTO `vehiclevendor` (`vehiclevendor_id`, `vendor_name`) VALUES
(1, 'JADWET TRADING COMPANY'),
(2, 'JAIN MOTORS'),
(3, 'MAHINDRA & MAHINDRA LIMITED'),
(4, 'MAHINDRA & MAHINDRA LIMITED (PROF-HO)'),
(5, 'MAHINDRA & MAHINDRA LTD'),
(6, 'T V SUNDRAM IYENGAR & SONS LIMITED'),
(7, 'T V SUNDRAM IYENGAR AND SONS LIMITED'),
(8, 'TV SUNDRAM IYENGAR & SONS LTD');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `vehiclevendor`
--
ALTER TABLE `vehiclevendor`
 ADD PRIMARY KEY (`vehiclevendor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `vehiclevendor`
--
ALTER TABLE `vehiclevendor`
MODIFY `vehiclevendor_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (293, NOW(), 'Sahil Gandhi','New_tables_vehiclevendor_&_asset');
