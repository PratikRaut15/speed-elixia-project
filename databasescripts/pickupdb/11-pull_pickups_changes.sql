DELIMITER $$
CREATE PROCEDURE `pull_pickups`(
	IN custnoparam INT
    , IN pickupidparam INT
    , IN clientidparam INT
    , IN vendornoparam INT
    , IN pickupboyidparam INT
    , IN pickupdateparam DATE
    , IN statusparam INT
    , IN pageIndex INT
	, IN pageSize INT
    ,OUT recordCount INT
)
BEGIN
	DECLARE fromRowNum INT DEFAULT 1;
	DECLARE toRowNum INT DEFAULT 1;
	SET fromRowNum = (pageIndex - 1) * pageSize + 1;
	SET toRowNum = (fromRowNum + pageSize) - 1;
	SET @rownum=0;
    
IF(custnoparam = 0 OR custnoparam = '') THEN
		SET custnoparam = NULL;
END IF;
IF(pickupidparam = 0 OR pickupidparam = '') THEN
		SET pickupidparam = NULL;
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


SET recordCount = (SELECT count(oid)  
FROM pickup_order as po
INNER JOIN vendormapping as vm on vm.customerid = po.customerid AND vm.vendor_no = po.vendorno AND vm.isdeleted = 0
LEFT JOIN pickup_customer as pc on po.customerid=pc.customerid and po.customerno = pc.customerno
LEFT JOIN pickup_vendor as pv on  vm.vendorid = pv.vendorid and po.customerno=pv.customerno
LEFT JOIN pickup_shiper as ps on po.shipperid=ps.sid and  po.customerno = ps.customerno
WHERE (po.customerno = custnoparam OR custnoparam IS NULL) 
AND  (po.oid = pickupidparam OR pickupidparam IS NULL) 
AND  (po.customerid = clientidparam OR clientidparam IS NULL) 
AND  (po.vendorno = vendornoparam OR vendornoparam IS NULL) 
AND  (po.pickupboyid = pickupboyidparam OR pickupboyidparam IS NULL) 
AND ((po.pickupdate BETWEEN pickupdateparam AND adddate(pickupdateparam, INTERVAL 1 day)) OR pickupdateparam IS NULL)
AND  (po.status = statusparam OR statusparam IS NULL) 
AND  po.isdeleted=0);


/* If pageSize is -1, it means we need to give all the records in a single page */
	IF (pageSize = -1) THEN
		SET pageSize = recordCount;
    END IF;
    
	SET fromRowNum = (pageIndex - 1) * pageSize + 1;
	SET toRowNum = (fromRowNum + pageSize) - 1;
	SET @rownum=0;
    


SELECT rownum oid, orderid, awbno, fulfillmentid,customerid, customerno, vendorid,
vendorname, sname, pickupboyid, customername, pickupdate, address, vendorno FROM
(SELECT @rownum:=@rownum + 1 AS rownum  
  ,oid
  ,po.orderid 
  ,awbno
  ,fulfillmentid
  ,po.customerno
  ,po.customerid
  ,po.vendorno
  ,pv.vendorid
  ,pv.vendorname
  ,ps.sname
  ,po.pickupboyid
  ,pc.customername
  ,po.pickupdate
  ,pv.address
FROM pickup_order as po
INNER JOIN vendormapping as vm on vm.customerid = po.customerid AND vm.vendor_no = po.vendorno AND vm.isdeleted = 0
LEFT JOIN pickup_customer as pc on po.customerid=pc.customerid and po.customerno = pc.customerno
LEFT JOIN pickup_vendor as pv on  vm.vendorid = pv.vendorid and vm.customerid = pv.customerno
LEFT JOIN pickup_shiper as ps on po.shipperid=ps.sid and  po.customerno = ps.customerno
WHERE (po.customerno = custnoparam OR custnoparam IS NULL) 
AND  (po.oid = pickupidparam OR pickupidparam IS NULL) 
AND  (po.customerid = clientidparam OR clientidparam IS NULL) 
AND  (po.vendorno = vendornoparam OR vendornoparam IS NULL) 
AND  (po.pickupboyid = pickupboyidparam OR pickupboyidparam IS NULL) 
AND ((po.pickupdate BETWEEN pickupdateparam AND adddate(pickupdateparam, INTERVAL 1 day)) OR pickupdateparam IS NULL)
AND  (po.status = statusparam OR statusparam IS NULL) 
AND  (po.isdeleted=0) ) 
allPickups
WHERE	rownum BETWEEN fromRowNum AND toRowNum;

END



INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 11, NOW(), 'Ganesh Papde','Pull Pickups SP For HyperLocal');
