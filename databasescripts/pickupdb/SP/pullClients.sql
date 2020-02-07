/*
    Name            -	pull_clients
    Description 	-	pull client details from pickup db
    Parameters		-	custnoparam
    Module			-   HYEPR LOCAL MODULE
    Sub-Modules 	- 	PICKUP MODULE
    Sample Call		-	eg. call
                        
    Created by		-	Shrikant
    Created on		- 	8 Dec, 2015
    Change details 	-	
    1) 	Updated by	- 	Shrikant
        Updated	on	- 	8 Dec, 2015
        Reason		-	
    2) 
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS pull_clients$$
CREATE PROCEDURE pull_clients(
	IN custnoparam INT
)
BEGIN
  SELECT 
    customerid
    , customername
  FROM pickup_customer as pc
  WHERE (pc.customerno = custnoparam OR custnoparam IS NULL)
  AND isdeleted = 0;

END$$
DELIMITER ;