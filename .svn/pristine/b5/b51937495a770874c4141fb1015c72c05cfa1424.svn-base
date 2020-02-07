SET GLOBAL event_scheduler = ON;
/*
    Name			-	event_ResetActualShare
    Description 	-	Calls the SP which would reset the actual share on monthly basis.
    Parameters		-	None
    Module			-	TMS
    Sub-Modules 	- 	
    Sample Call		-	Trriggered automatically by mysql server
    Created by		-	Mrudang
    Created on		- 	16 Jan, 2016
    Change details 	-	
    1) 	Updated by	- 	
		Updated	on	- 	
        Reason		-	
*/
DELIMITER $$
DROP EVENT IF EXISTS event_reset_transporteractualshare$$
CREATE EVENT `event_reset_transporteractualshare`
ON SCHEDULE EVERY 1 MONTH
STARTS '2016-01-01 04:00:00'
DO BEGIN
    CALL eventsp_ResetActualShare();
END$$