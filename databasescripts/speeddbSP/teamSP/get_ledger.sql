/*
    Name		-	get_ledger
    Description 	-	get Ledger Details
    Parameters		-	ledgeridparam INT,ledgernameparam VARCHAR(100)
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_ledger(2,'fedex');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-
    1) 	Updated by	- 	Sahil
	    Updated	on	- 	30 April, 2016
        Reason		-	New SP.
    1)  Updated by  -   Shrikant
        Updated on  -   25 Jan 2017
        Reason      -   Change in search condition for ledgername - Check "=" instead if "LIKE".
*/

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

    SELECT  l.ledgerid
            ,l.ledgername
            ,l.address1
            ,l.address2
            ,l.address3
            ,l.state_code
            ,l.email
            ,l.phone
            ,l.pan_no
            ,l.gst_no
            ,l.cst_no
            ,l.st_no
            ,l.vat_no
            ,l.createdby
            ,l.createdon
            ,l.updatedby
            ,l.updatedon
            ,sgc.`state`
    FROM    ledger AS l
    INNER JOIN state_gst_code sgc ON sgc.codeid = l.state_code
    WHERE   (l.ledgerid  = ledgeridparam OR ledgeridparam IS NULL)
    AND     (TRIM(l.ledgername) = TRIM(ledgernameparam) OR ledgernameparam IS NULL)
    AND     l.isdeleted = 0
    ORDER BY l.ledgerid DESC;

END$$
DELIMITER ;

