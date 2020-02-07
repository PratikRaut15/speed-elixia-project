-- Insert SQL here.

ALTER TABLE `team` ADD `member_type` TINYINT(1) NOT NULL DEFAULT '0' AFTER `role`;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 201, NOW(), 'Ganesh','Elixir/Non-elixir');
