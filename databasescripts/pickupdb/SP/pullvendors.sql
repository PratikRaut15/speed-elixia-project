/*
    Name            -	pull_vendors
    Description 	-	pull vendor details form pickupdb
    Parameters		-	custnoparam, useridparam
    Module			-   HYPER LOCAL MODULE
    Sub-Modules 	- 	PICKUP MODULE
    Sample Call		-	eg. call call pullvendors('127','870');
                        
    Created by		-	Shrikant
    Created on		- 	9 Dec, 2015
    Change details 	-	
    1) 	Updated by	- 	
        Updated	on	- 	
        Reason		-	
    2) 
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS pull_vendors$$
CREATE PROCEDURE pull_vendors(
	IN custnoparam INT
    , IN useridparam INT
)
BEGIN

IF(custnoparam = 0 OR custnoparam = '') THEN
		SET custnoparam = NULL;
END IF;

IF(useridparam = 0 OR useridparam = '') THEN
		SET useridparam = NULL;
END IF;
  SELECT 
	 vendorid
    , vendorname
    , address
  FROM pickup_vendor as pv
  INNER JOIN pinmapping as pm on pm.pincode = pv.pincode
  WHERE (pv.customerno = custnoparam OR custnoparam IS NULL) 
  AND (pm.pid = useridparam OR useridparam IS NULL)
  AND pv.isdeleted = 0;
END$$
DELIMITER ;