-- Insert SQL here.
ALTER TABLE `user`
  DROP `rt_status_filter`,
  DROP `rt_stoppage_filter`; 


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 113, NOW(), 'Shrikanth Suryawanshi', '113-Remove Status and Stoppage Filter');
