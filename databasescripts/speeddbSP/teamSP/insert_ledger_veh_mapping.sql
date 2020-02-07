/*
    Name		-	insert_ledger_veh_mapping
    Description 	-	INSERT Ledger with vehicleid 
    Parameters		-	vehicleid INT,ledgerid INT,customernoparam INT,createdby INT ,createdon DATETIME,updatedby INT,updatedon 					DATETIME
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	insert_ledger_veh_mapping(134,2,4,6,'2016-04-23 15:00:32',6,'2016-04-23 15:00:32');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	16 April, 2016
        Reason		-	New SP.
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_ledger_veh_mapping`$$
CREATE PROCEDURE `insert_ledger_veh_mapping`( 
    IN vehicleid INT
    , IN ledgerid INT
	, IN customerno INT
    , IN createdby INT
    , IN createdon DATETIME
    , IN updatedby INT
    , IN updatedon DATETIME
)
BEGIN
INSERT INTO ledger_veh_mapping
(
	ledgerid
    ,vehicleid
    ,customerno
    , createdby 
    , createdon 
    , updatedby 
	, updatedon 
) VALUES
(
	ledgerid
    ,vehicleid
    ,customerno
    , createdby 
    , createdon 
    , updatedby 
	, updatedon 
)
    ;
END$$
DELIMITER ;

