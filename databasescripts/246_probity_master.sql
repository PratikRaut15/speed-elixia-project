-- Insert SQL here.

INSERT INTO `probity_master` (`pmid`, `workkey`, `workkey_name`, `work_master`, `customerno`) VALUES

(34, '1106', 'Padgha To SN Road', 'static_45.sqlite', 15);

Update `probity_master` SET customerno=21 where pmid=4;



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 246, NOW(), 'Shrikant Suryawanshi','Probity Master Addition');
