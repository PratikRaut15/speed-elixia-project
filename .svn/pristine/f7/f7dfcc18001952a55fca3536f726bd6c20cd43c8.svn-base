/*
    Name		-	unit_of_cust
    Description 	-	details of unit assigneed to team member.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL unit_of_cust(5);
    Created by		-	Arvind
    Created on		- 	23 Nov, 2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS unit_sim_veh_of_cust$$
CREATE PROCEDURE unit_sim_veh_of_cust(
	IN customernoVar INT
	)
BEGIN
SELECT unit.uid, unit.unitno,simcard.id,simcard.simcardno,vehicle.vehicleid,vehicle.vehicleno FROM vehicle
    INNER JOIN unit ON unit.uid=vehicle.uid
    INNER JOIN devices ON devices.uid=unit.uid
    LEFT OUTER JOIN simcard ON simcard.id=devices.simcardid
    WHERE vehicle.customerno=customernoVar AND isdeleted=0;

END$$
DELIMITER ;

-- CALL unit_sim_veh_of_cust(2);