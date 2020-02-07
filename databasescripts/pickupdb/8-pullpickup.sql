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

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 8, NOW(), 'Shrikant Suryawanshi','Pull Pickups SP For HyperLocal');
