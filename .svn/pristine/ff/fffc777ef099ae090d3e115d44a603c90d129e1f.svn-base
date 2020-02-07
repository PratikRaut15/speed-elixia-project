/*
    Name		-	resetSMSCount
    Description 	-	reset the sms_count column value to zero in vehicle and user table
    Parameters		-	
    Module		-	SPEED
    Sub-Modules 	- 	No
    Sample Call		-	CALL resetSMSCount();
    Created by		-	Arvind
    Created on		- 	13 Dec,2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `resetSMSCount`$$
CREATE PROCEDURE `resetSMSCount`()
BEGIN
    
    UPDATE `vehicle` SET sms_count=0 WHERE sms_lock=0 AND isdeleted=0;
    
    UPDATE `user` SET sms_count=0 WHERE sms_lock=0 AND isdeleted=0;
    
END$$
DELIMITER ; 