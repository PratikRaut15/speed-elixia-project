-- Insert SQL here.


ALTER TABLE `user` CHANGE `thhistory` `thhistorypdf` TINYINT(1) NOT NULL;

ALTER TABLE `user`  ADD `thhistorycsv` TINYINT(1) NOT NULL;

ALTER TABLE `user`  ADD `gensetpdf` TINYINT(1) NOT NULL;

ALTER TABLE `user`  ADD `gensetcsv` TINYINT(1) NOT NULL;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 25, NOW(), 'Ajay Tripathi','change travel history column to pdf in user table and add genset pdf and csv');