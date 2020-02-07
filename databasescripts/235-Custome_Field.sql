-- Insert SQL here.

INSERT INTO `customtype` (`id`, `name`) VALUES
(15, 'Reference Number');

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 235, NOW(), 'Shrikant Suryawanshi','Custome Field');
