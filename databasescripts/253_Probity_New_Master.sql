-- Insert SQL here.

INSERT INTO `probity_master` (`pmid`, `workkey`, `workkey_name`, `work_master`, `customerno`) VALUES

(40, '1104', 'Powai Plant To BR Road', 'static_52.sqlite', 21);




-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 253, NOW(), 'Shrikant Suryawanshi','Probity New Master');
