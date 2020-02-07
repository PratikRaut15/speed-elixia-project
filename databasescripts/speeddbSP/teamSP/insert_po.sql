/*
    Name		-	insert_po
    Description 	-	Insert PO Details
    Parameters		-	pono VARCHAR(30),IN podate DATE,IN poamount INT,IN poexpiry DATE,IN description VARCHAR(50),customernoparam INT
				updatedby INT,updatedon DATETIME
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL insert_po('test321','2014-04-10',21,'2016-04-21 ','test description',2,6,'2016-04-15 15:21:00',6,'2016-04-15 15:21:00');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	16 April, 2016
        Reason		-	New SP.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_po`$$
CREATE PROCEDURE `insert_po`( 
    IN pono VARCHAR(30)
    ,IN podate DATE
    ,IN poamount INT
    ,IN poexpiry DATE
    ,IN description VARCHAR(50)
    ,IN customerno INT
    ,IN createdby INT
    ,IN createdon DATETIME
    ,IN updatedby INT
    ,IN updatedon DATETIME
)

BEGIN
INSERT INTO po
(
    pono
    ,podate
    ,poamount
    ,poexpiry
    ,description
    ,customerno
    ,createdby
    ,createdon
    ,updatedby
    ,updatedon
)VALUES(
pono
    ,podate
    ,poamount
    ,poexpiry
    ,description
    ,customerno
    ,createdby
    ,createdon
    ,updatedby
    ,updatedon
)
;
END$$
DELIMITER ;
