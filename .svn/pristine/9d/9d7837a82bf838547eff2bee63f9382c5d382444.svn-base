/*
    Name		-	delete_ledger_veh_mapping`
    Description 	-	Delete Ledger Vehicle mapping
    Parameters		-	ledger_veh_mapidparam INT,customernoparam INT,ledgeridparam INT,updatedby INT,updatedon DATETIME
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL delete_ledger_veh_mapping`(1,2,6,'2016-04-15 15:21:00');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	16 April, 2016
        Reason		-	New SP.
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_ledger_veh_mapping`$$
CREATE PROCEDURE `delete_ledger_veh_mapping`( 
    IN ledger_veh_mapidparam INT
    , IN customernoparam INT
    , IN ledgeridparam INT
    , IN updatedby INT
    , IN updatedon DATETIME
)
BEGIN
IF(ledger_veh_mapidparam = '' OR ledger_veh_mapidparam = '0') THEN
 SET ledger_veh_mapidparam = NULL;
END IF;

IF(customernoparam = '' OR customernoparam = '0') THEN
 SET customernoparam = NULL;
END IF;

IF(ledgeridparam = '' OR ledgeridparam = '0') THEN
 SET ledgeridparam = NULL;
END IF;
UPDATE 
 ledger_veh_mapping as lv
 SET lv.isdeleted = 1
 ,lv.updatedby = updatedby
 ,lv.updatedon = updatedon
WHERE (lv.ledger_veh_mapid  = ledger_veh_mapidparam OR ledger_veh_mapidparam IS NULL)
AND (lv.customerno = customernoparam OR customernoparam IS NULL)
AND (lv.ledgerid = ledgeridparam OR ledgeridparam IS NULL)
;
END$$
DELIMITER ;
