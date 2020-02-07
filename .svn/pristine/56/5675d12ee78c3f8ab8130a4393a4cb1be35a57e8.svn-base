-- Insert SQL here.
ALTER TABLE `sp_note` ADD `sendemailto` INT(11) NOT NULL AFTER `create_by`;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
 VALUES ( 211, NOW(), 'Ganesh','send mail when note add');
