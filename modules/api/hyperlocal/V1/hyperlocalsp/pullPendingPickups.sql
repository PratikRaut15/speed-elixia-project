/*
    Name            -	pull_pickups
    Description 	-	pull pickups from prickupdb
    Parameters		-	customerno, clientid, vendorno, pickupboyid, pickupdate, status
    Module			-   HYPER LOCAL MODULE
    Sub-Modules 	- 	PICKUP MODULE
    Sample Call		-	eg. call CALL pull_pickups('127','0','0','0','2015-12-09','0');
                        
    Created by		-	Shrikant
    Created on		- 	9 Dec, 2015
    Change details 	-	
    1) 	Updated by	- 	
        Updated	on	- 	
        Reason		-	
    2) 
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS pull_pickups$$
CREATE PROCEDURE pull_pickups(
	IN custnoparam INT
    , IN clientidparam INT
    , IN vendornoparam INT
    , IN pickupboyidparam INT
    , IN pickupdateparam DATE
    , IN statusparam INT
)
BEGIN

IF(custnoparam = 0 OR custnoparam = '') THEN
		SET custnoparam = NULL;
END IF;
IF(clientidparam = 0 OR clientidparam = '') THEN
		SET clientidparam = NULL;
END IF;
IF(vendornoparam = 0 OR vendornoparam = '') THEN
		SET vendornoparam = NULL;
END IF;
IF(pickupboyidparam = 0 OR pickupboyidparam = '') THEN
		SET pickupboyidparam = NULL;
END IF;
IF(pickupdateparam = 0 OR pickupdateparam = '') THEN
		SET pickupdateparam = NULL;
END IF;
IF(statusparam = '') THEN
		SET statusparam = NULL;
END IF;

SELECT   
   oid
  ,awbno
  ,fulfillmentid
  ,po.customerno
  ,po.customerid
  ,po.vendorno
  ,pv.vendorid
FROM pickup_order as po
INNER JOIN vendormapping as vm on vm.customerid = po.customerid AND vm.vendor_no = po.vendorno AND vm.isdeleted = 0
LEFT JOIN pickup_customer as pc on po.customerid=pc.customerid and po.customerno = pc.customerno
LEFT JOIN pickup_vendor as pv on  vm.vendorid = pv.vendorid and po.customerno=pv.customerno
LEFT JOIN pickup_shiper as ps on po.shipperid=ps.sid and  po.customerno = ps.customerno
WHERE (po.customerno = custnoparam OR custnoparam IS NULL) 
AND  (po.customerid = clientidparam OR clientidparam IS NULL) 
AND  (po.vendorno = vendornoparam OR vendornoparam IS NULL) 
AND  (po.pickupboyid = pickupboyidparam OR pickupboyidparam IS NULL) 
AND  (po.pickupdate = pickupdateparam OR pickupdateparam IS NULL) 
AND  (po.status = statusparam OR statusparam IS NULL) 
AND  po.isdeleted=0; 

END$$
DELIMITER ;