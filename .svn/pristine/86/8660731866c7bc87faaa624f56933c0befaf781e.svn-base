-- Insert SQL here.

INSERT INTO `customtype` (`id`, `name`) VALUES (NULL, 'Status');
UPDATE customer SET use_genset_sensor='0', use_extradigital='1' where customerno=59;
UPDATE unit SET extra_digital='1' where customerno=59;
Update unit SET extra_digital='2' where customerno=59 and unitno='900319';
Update unit SET extra_digital='2' where customerno=59 and unitno='100527';
Update unit SET extra_digital='2' where customerno=59 and unitno='100611';

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 283, NOW(), 'Shrikant','Extra Digital Sensor For Customerno 59');
