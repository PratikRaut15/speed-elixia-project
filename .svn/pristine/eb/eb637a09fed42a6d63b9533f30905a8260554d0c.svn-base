/*
    Name		-	get_ledger_cust_mapping
    Description 	-	Map Ledger Details to Customers 
    Parameters		-	ledgeridparam INT,customernoparam INT,ledgernameparam VARCHAR(100)
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_ledger_cust_mapping(1,2,'fedex');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	16 April, 2016
        Reason		-	New SP.
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `get_ledger_cust_mapping`$$
CREATE PROCEDURE `get_ledger_cust_mapping`( 
    IN ledgeridparam INT
    ,IN customernoparam INT
    , IN ledgernameparam VARCHAR(100)
)
BEGIN
	IF(ledgeridparam = '' OR ledgeridparam = '0') THEN
		SET ledgeridparam = NULL;
	END IF;

	IF(ledgernameparam = '' OR ledgernameparam = '0') THEN
		SET ledgernameparam = NULL;
	END IF;
    
    IF(customernoparam = '' OR customernoparam = '0') THEN
		SET customernoparam = NULL;
	END IF;
    
SELECT 	l.ledgerid
		,l.ledgername
        ,l.address1
        ,l.address2
        ,l.address3
        ,l.email
        ,l.phone
        ,l.pan_no
        ,l.cst_no
        ,l.st_no
        ,l.vat_no
        ,lc.customerno
FROM ledger_cust_mapping AS lc
INNER JOIN ledger AS l ON l.ledgerid = lc.ledgerid
WHERE (lc.ledgerid  = ledgeridparam OR ledgeridparam IS NULL)
AND (lc.customerno  = customernoparam OR customernoparam IS NULL)
AND (l.ledgername LIKE CONCAT('%', ledgernameparam, '%') OR ledgernameparam IS NULL)
AND lc.isdeleted = 0
;     
END$$
DELIMITER ;

