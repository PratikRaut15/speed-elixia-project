/*table contactperson_details */
DROP TABLE IF EXISTS `contactperson_details`;
CREATE TABLE IF NOT EXISTS `contactperson_details` (
`cpdetailid` int(11) NOT NULL,
  `typeid` int(11) NOT NULL,
  `person_name` varchar(30) NOT NULL,
  `cp_email1` varchar(30) NOT NULL,
  `cp_email2` varchar(30) NOT NULL,
  `cp_phone1` varchar(15) NOT NULL,
  `cp_phone2` varchar(15) NOT NULL,
  `customerno` int(11) NOT NULL,
  `insertedby` int(11) NOT NULL,
  `insertedon` datetime DEFAULT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL
);

ALTER TABLE `contactperson_details`
 ADD PRIMARY KEY (`cpdetailid`);

ALTER TABLE `contactperson_details`
MODIFY `cpdetailid` int(11) NOT NULL AUTO_INCREMENT;

/*table contactperson_type_master */
DROP TABLE IF EXISTS `contactperson_type_master`;
CREATE TABLE IF NOT EXISTS `contactperson_type_master` (
`person_typeid` int(11) NOT NULL,
  `person_type` varchar(30) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;


INSERT INTO `contactperson_type_master` (`person_typeid`, `person_type`) VALUES
(1, 'Owner'),
(2, 'Accounts'),
(3, 'Co-ordinator');

ALTER TABLE `contactperson_type_master`
 ADD PRIMARY KEY (`person_typeid`);

ALTER TABLE `contactperson_type_master`
MODIFY `person_typeid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES (349, NOW(), 'Sahil','new contact person details tables for team');
