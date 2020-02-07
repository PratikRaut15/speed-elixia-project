/*
    Name		-	insert_ledger_cust_mapping
    Description 	-	insert Customerno Details to Ledger 
    Parameters		-	ledgeridparam INT,customernoparam INT,ledgernameparam VARCHAR(100),createdby INT,createdon DATETIME,updatedby 					INT,updatedon DATETIME
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL insert_ledger_cust_mapping(1,2,6,'2016-04-16 15:23:00',6,'2016-04-16 15:23:00');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	16 April, 2016
        Reason		-	New SP.
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_ledger_cust_mapping`$$
CREATE PROCEDURE `insert_ledger_cust_mapping`(
	IN ledgerid INT
    , IN customerno INT
    , IN createdby INT
    , IN createdon DATETIME
    , IN updatedby INT
    , IN updatedon DATETIME
)
BEGIN
INSERT INTO ledger_cust_mapping
(
	ledgerid
    ,customerno
    , createdby 
    , createdon 
    , updatedby 
	, updatedon 
) VALUES
(
	ledgerid
    ,customerno
    , createdby 
    , createdon 
    , updatedby 
	, updatedon 
)
    ;
END$$
DELIMITER ;
