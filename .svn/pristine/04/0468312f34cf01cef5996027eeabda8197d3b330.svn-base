-- Insert SQL here.

ALTER TABLE driver
ADD COLUMN  `birthdate` date NOT NULL AFTER `userid`,
ADD COLUMN  `age` int(15) NOT NULL,
ADD COLUMN  `bloodgroup` varchar(50) NOT NULL,
ADD COLUMN  `licence_validity` date NOT NULL,
ADD COLUMN  `licence_issue_auth` varchar(255) NOT NULL,
ADD COLUMN  `local_address` varchar(255) NOT NULL,
ADD COLUMN  `local_contact` varchar(50) NOT NULL,
ADD COLUMN  `local_contact_mob` varchar(50) NOT NULL,
ADD COLUMN  `emergency_contact1` varchar(150) NOT NULL,
ADD COLUMN  `emergency_contact2` varchar(150) NOT NULL,
ADD COLUMN  `emergency_contact_no1` varchar(50) NOT NULL,
ADD COLUMN  `emergency_contact_no2` varchar(50) NOT NULL,
ADD COLUMN  `native_address` varchar(255) NOT NULL,
ADD COLUMN  `native_contact` varchar(50) NOT NULL,
ADD COLUMN  `native_contact_mob` varchar(50) NOT NULL,
ADD COLUMN  `native_emergency_contact1` varchar(50) NOT NULL,
ADD COLUMN  `native_emergency_contact2` varchar(50) NOT NULL,
ADD COLUMN  `native_emergency_contact_no1` varchar(50) NOT NULL,
ADD COLUMN  `native_emergency_contact_no2` varchar(50) NOT NULL,
ADD COLUMN  `previous_employer` varchar(255) NOT NULL,
ADD COLUMN  `service_period` int(15) NOT NULL,
ADD COLUMN  `service_contact_person` varchar(100) NOT NULL,
ADD COLUMN  `service_contact_no` varchar(50) NOT NULL,
ADD COLUMN  `upload` varchar(250) NOT NULL;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 260, NOW(), 'Ganesh','Driver alter table');
