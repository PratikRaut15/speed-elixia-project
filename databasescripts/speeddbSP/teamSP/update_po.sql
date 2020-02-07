/*
    Name		-	update_po
    Description 	-	Update PO Details
    Parameters		-	pono VARCHAR(30),IN podate DATE,IN poamount INT,IN poexpiry DATE,IN description VARCHAR(50),customernoparam INT
				updatedby INT,updatedon DATETIME
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL update_po('test','2014-04-10',21,'2016-04-21','test description',2,6,'2016-04-15 15:21:00');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	16 April, 2016
        Reason		-	New SP.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `update_po`$$
CREATE PROCEDURE `update_po`( 
IN poidparam INT
    ,IN pono VARCHAR(30)
    ,IN podate DATE
    ,IN poamount INT
    ,IN poexpiry DATE
    ,IN description VARCHAR(50)
    ,IN customernoparam INT
    ,IN updatedby INT
    ,IN updatedon DATETIME
)

BEGIN
UPDATE po
SET
    pono = pono
    ,podate = podate
    ,poamount = poamount
    ,poexpiry = poexpiry
    ,description = description
    ,updatedby = updatedby
    ,updatedon = updatedon
WHERE 
customerno = customernoparam
AND poid = poidparam
AND isdeleted = 0
;
END$$
DELIMITER ;
