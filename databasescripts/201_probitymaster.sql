-- Insert SQL here.

INSERT INTO `probity_master` (`pmid`, `workkey`, `workkey_name`, `work_master`) VALUES
(6, '1036', 'Powai Police Station to RMC', 'static_6.sqlite'),
(7, '1026', 'Powai RMC to Shivaji Kuttir  Mandal Road', 'static_7.sqlite'),
(8, '1043', 'LBS Road Side Strip fromShivajiKutir to Gulab Shah to Powai RMC', 'static_8.sqlite'),
(9, '1030', 'Powai RMC to Masrani Lane', 'static_9.sqlite'),
(10, '1029', 'Hall Road(hallow pool road) to Powai RMC', 'static_11.sqlite'),
(11, '1028', 'Powai RMC Plant to New Hall Road', 'static_11.sqlite');

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 201, NOW(), 'Shrikant Suryawanshi','probity ');
