-- Insert SQL here.

CREATE TABLE `probity_master` (
`pmid` int(11) NOT NULL,
  `workkey` varchar(20) NOT NULL,
  `workkey_name` varchar(150) NOT NULL,
  `work_master` varchar(50) NOT NULL
);


INSERT INTO `probity_master` (`pmid`, `workkey`, `workkey_name`, `work_master`) VALUES
(1, '1104', 'Bal Rajeshwar Road', 'static_1.sqlite'),
(2, '1105', 'Junction Of Din. LBS Marg', 'static_2.sqlite'),
(3, '1103', 'Yogi Hill Road', 'static_3.sqlite'),
(4, '1106', 'S L Road To S N Road', 'static_4.sqlite'),
(5, '1107', 'Vidhyalaya Road', 'static_5.sqlite');	

ALTER TABLE `probity_master` ADD PRIMARY KEY (`pmid`);

ALTER TABLE batch add pmid int(11) NOT NULL AFTER dummybatch;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 185, NOW(), 'Shrikanth Suryawasnhi','Probity Changes');
