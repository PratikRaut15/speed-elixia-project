
UPDATE vehicle 
SET temp1_min=(@tempVar:=temp1_min), temp1_min = temp1_max, temp1_max = @tempVar
WHERE `customerno` =177
AND temp1_min > temp1_max
AND isdeleted =0;

UPDATE vehicle 
SET temp2_min=(@tempVar:=temp2_min), temp2_min = temp2_max, temp2_max = @tempVar
WHERE `customerno` =177
AND temp2_min > temp2_max
AND isdeleted =0;

UPDATE vehicle 
SET temp3_min=(@tempVar:=temp3_min), temp3_min = temp3_max, temp3_max = @tempVar
WHERE `customerno` =177
AND temp3_min > temp3_max
AND isdeleted =0;

UPDATE vehicle 
SET temp4_min=(@tempVar:=temp4_min), temp4_min = temp4_max, temp4_max = @tempVar
WHERE `customerno` =177
AND temp4_min > temp4_max
AND isdeleted =0;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (344, NOW(), 'Mrudang Vora','Fassos - Swap temperature range values');