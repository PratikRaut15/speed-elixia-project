DELIMITER $$
DROP PROCEDURE IF EXISTS `get_ledger`$$
CREATE PROCEDURE `get_ledger`( 
    IN ledgeridparam INT
    , IN ledgernameparam VARCHAR(100)
)
BEGIN
	IF(ledgeridparam = '' OR ledgeridparam = '0') THEN
		SET ledgeridparam = NULL;
	END IF;

	IF(ledgernameparam = '' OR ledgernameparam = '0') THEN
		SET ledgernameparam = NULL;
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
        ,l.createdby
		,l.createdon
        ,l.updatedby
        ,l.updatedon
FROM ledger AS l
WHERE (l.ledgerid  = ledgeridparam OR ledgeridparam IS NULL)
AND (l.ledgername LIKE CONCAT('%', ledgernameparam, '%') OR ledgernameparam IS NULL)
AND l.isdeleted = 0
ORDER BY l.ledgerid DESC
;     
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_ledger`$$
CREATE PROCEDURE `insert_ledger`( 
    IN ledgername VARCHAR(100)
    , IN address1 VARCHAR(100)
    , IN address2 VARCHAR(100)
    , IN address3 VARCHAR(100)
    , IN email VARCHAR(40)
    , IN phone VARCHAR(20)
    , IN pan_no VARCHAR(30)
    , IN cst_no VARCHAR(30)
    , IN st_no VARCHAR(30)
    , IN vat_no VARCHAR(30)
    , IN createdby INT
    , IN createdon DATETIME
    , IN updatedby INT
    , IN updatedon DATETIME
    , OUT lastinsertid  INT
)
BEGIN
DECLARE lastinsertid INT;
INSERT INTO ledger
(
	ledgername
    , address1 
    , address2 
    , address3
    , email 
    , phone 
    , pan_no 
    , cst_no 
    , st_no 
    , vat_no 
    , createdby 
    , createdon 
    , updatedby 
	, updatedon 
) VALUES
(
	ledgername 
    , address1 
    , address2 
    , address3
    , email 
    , phone 
    , pan_no 
    , cst_no 
    , st_no 
    , vat_no 
    , createdby 
    , createdon 
    , updatedby 
	, updatedon
)
    ;
SET    lastinsertid = LAST_INSERT_ID(); 
    
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_ledger`$$
CREATE PROCEDURE `delete_ledger`( 
	IN ledgeridparam INT
	, IN updatedby INT
    , IN updatedon DATETIME
)
BEGIN
	UPDATE ledger SET 
    isdeleted = 1
    ,updatedby = updatedby
    ,updatedon = updatedon
    WHERE ledgerid = ledgeridparam
    ;
    /* DELETE LEDGER MAPPING WITH VEHICLE */
    CALL `delete_ledger_veh_mapping`(0,0,ledgeridparam,updatedby,updatedon);    
END$$
DELIMITER ;

-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
		, isapplied =1 
WHERE 	patchid = 383;
