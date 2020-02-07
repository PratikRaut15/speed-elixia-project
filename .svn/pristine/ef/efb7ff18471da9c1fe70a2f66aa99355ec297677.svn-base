-- Insert SQL here.

ALTER TABLE `comhistory`
  DROP `type`,
  DROP `status`,
  DROP `lat`,
  DROP `long`,
  DROP `vehicleid`,
  DROP `timeadded`;

ALTER TABLE `comhistory`  ADD `comqid` INT(11) NOT NULL AFTER `cqhid`;

ALTER TABLE `eventalerts` ADD INDEX(`vehicleid`) ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 34, NOW(), 'Ajay Tripathi','modify comhistory and Event Alert Indexing');
