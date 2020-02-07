/*
    Name		-	get_ledger_veh_mapping
    Description 	-	GET Mapped Ledger Details with vehicle 
    Parameters		-	ledger_veh_mapidparam INT,customernoparam INT,IN ledgeridparam INT,vehiclenoparam VARCHAR(20)
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	get_ledger_veh_mapping(1,2,4,'MH 02 AP 2514');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    Change details 	-	
    1) 	Updated by	- 	Mrudang Vora
		Updated	on	- 	07 June, 2016
		Reason		-	Team Ledger Vehicle Mapping Issue (Added Joins with device and unit)
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `get_ledger_veh_mapping`$$
CREATE PROCEDURE `get_ledger_veh_mapping`( 
    IN ledger_veh_mapidparam INT
    , IN customernoparam INT
    , IN ledgeridparam INT
    , IN vehiclenoparam VARCHAR(20)
)
BEGIN

    IF(ledger_veh_mapidparam = '' OR ledger_veh_mapidparam = '0') THEN
     SET ledger_veh_mapidparam = NULL;
    END IF;

    IF(customernoparam = '' OR customernoparam = '0') THEN
     SET customernoparam = NULL;
    END IF;

    IF(vehiclenoparam = '' OR vehiclenoparam = '0') THEN
     SET vehiclenoparam = NULL;
    END IF;

    IF(ledgeridparam = '' OR ledgeridparam = '0') THEN
     SET ledgeridparam = NULL;
    END IF;

    SELECT 	l.ledger_veh_mapid
            ,l.ledgerid
            ,l.vehicleid
            ,l.customerno
            ,v.vehicleno
            ,l.createdby
            ,l.createdon
            ,l.updatedby
            ,l.updatedon
            ,v.uid
    FROM    ledger_veh_mapping as l
    INNER JOIN vehicle as v ON l.vehicleid = v.vehicleid
    INNER JOIN unit as u ON u.vehicleid = v.vehicleid
    INNER JOIN devices as d ON d.uid = u.uid
    WHERE (l.ledger_veh_mapid  = ledger_veh_mapidparam OR ledger_veh_mapidparam IS NULL)
    AND     (l.customerno = customernoparam OR customernoparam IS NULL)
    AND     (v.customerno = customernoparam OR customernoparam IS NULL)
    AND     (l.ledgerid = ledgeridparam OR ledgeridparam IS NULL)
    AND     (v.vehicleno LIKE CONCAT('%', vehiclenoparam, '%') OR vehiclenoparam IS NULL)
    AND     l.isdeleted = 0
    ORDER BY v.vehicleno ASC;

END$$
DELIMITER ;

