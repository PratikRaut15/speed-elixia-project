SET @patchId = 729;
SET @patchDate = '2019-10-11 04:20:00';
SET @patchOwner = 'Pratik Raut';
SET @patchDescription = 'Notes Functionality Changes';


INSERT INTO dbpatches(patchid, patchdate, appliedby, patchdesc, isapplied)
VALUES (@patchId, @patchDate, @patchOwner, @patchDescription, '0');

--
-- Table structure for table `vehiclenotes`
--

CREATE TABLE `vehiclenotes` (
  `noteId` int(10) UNSIGNED NOT NULL,
  `vehicleId` int(11) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `customerNo` int(11) NOT NULL DEFAULT 0,
  `createdBy` int(11) NOT NULL DEFAULT 0,
  `createdOn` datetime DEFAULT NULL,
  `updatedBy` int(11) NOT NULL DEFAULT 0,
  `updatedOn` datetime DEFAULT NULL,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `vehiclenotes`
--
ALTER TABLE `vehiclenotes`
  ADD PRIMARY KEY (`noteId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `vehiclenotes`
--
ALTER TABLE `vehiclenotes`
  MODIFY `noteId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

UPDATE dbpatches SET isapplied = 1, updatedOn =  DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE) WHERE patchid = @patchId;
