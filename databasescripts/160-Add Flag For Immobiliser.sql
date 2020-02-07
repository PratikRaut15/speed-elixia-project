-- Insert SQL here.


ALTER TABLE unit ADD mobiliser_flag tinyint(1) NOT NULL after is_mobiliser;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 160, NOW(), 'Shrikanth Suryawanshi','Add Flag For Immobiliser');
