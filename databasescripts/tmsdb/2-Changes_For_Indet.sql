DELIMITER $$
DROP PROCEDURE IF EXISTS get_indent$$
CREATE PROCEDURE get_indent(
	IN custno INT
    , IN indentidparam INT
    , IN factoryidparam INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(indentidparam = '' OR indentidparam = 0) THEN
		SET indentidparam = NULL;
	END IF;
    IF(factoryidparam = '' OR factoryidparam = 0) THEN
		SET factoryidparam = NULL;
	END IF;
    
	SELECT 
    i.indentid,
    t.transporterid,
    t.transportername,
    i.vehicleno,
    i.proposedindentid,
    i.proposed_vehicletypeid,
    i.actual_vehicletypeid,
    i.factoryid,
    f.factoryname,
    i.depotid,
    d.depotname,
    vehtype.vehiclecode AS proposedvehiclecode,
    vehtype.vehicledescription as proposedvehicledescription,
    veh.vehiclecode AS actualvehiclecode,
    veh.vehicledescription as actualvehicledescription,
    i.date_required,
    i.loadstatus,
    shipmentno,
    i.remarks,
    totalweight,
    totalvolume,
    isdelivered,
    i.customerno,
    i.created_on,
    i.created_by,
    i.updated_on,
    i.updated_by
FROM
    indent i
        INNER JOIN
    transporter t ON t.transporterid = i.transporterid
        INNER JOIN
    factory f ON f.factoryid = i.factoryid
        INNER JOIN
    depot d ON d.depotid = i.depotid
        INNER JOIN
    vehicletype vehtype ON vehtype.vehicletypeid = i.proposed_vehicletypeid
        LEFT JOIN
    vehicletype veh ON veh.vehicletypeid = i.actual_vehicletypeid
       
WHERE
    (i.customerno = custno OR custno IS NULL)
        AND (i.indentid = indentidparam
        OR indentidparam IS NULL)
        AND (i.factoryid = factoryidparam
        OR factoryidparam IS NULL)
        AND i.isdeleted = 0;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_indent_sku_mapping`$$
CREATE PROCEDURE `get_indent_sku_mapping`(
	IN custno INT
       , indentidparam INT
)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    	IF(indentidparam = '' OR indentidparam = 0) THEN
		SET indentidparam = NULL;
	END IF;
	SELECT 	        
		ism.indentid
		, s.skuid
		, s.skucode
		, s.sku_description
		, ism.no_of_units
		, ism.customerno
		, ism.created_on
		, ism.updated_on 
		, ism.created_by
		, ism.updated_by
	FROM 	indent_sku_mapping as ism
	INNER JOIN sku s ON s.skuid = ism.skuid
	WHERE 	(ism.customerno = custno OR custno IS NULL)
    	AND	(ism.indentid = indentidparam OR indentidparam IS NULL)
	AND 	ism.isdeleted = 0;
END$$
DELIMITER ;

CREATE TABLE `smslog` (
  `smsid` int(11) NOT NULL AUTO_INCREMENT,
  `mobileno` varchar(50) NOT NULL,
  `message` varchar(500) NOT NULL,
  `response` varchar(500) NOT NULL,
  `proposedindentid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `issmssent` tinyint(1) DEFAULT '0',
  `inserted_datetime` datetime DEFAULT NULL,
  `isdeleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`smsid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DELIMITER $$
DROP PROCEDURE IF EXISTS insert_smslog$$
CREATE PROCEDURE `insert_smslog`(
IN mobilenoparam VARCHAR(10)
, IN messageparam VARCHAR(250)
, IN responseparam VARCHAR(250)
, IN proposedindentidparam INT
, IN smssentparam TINYINT
, IN customernoparam INT
, IN todaysdate DATETIME
, OUT smsid INT
)
BEGIN

INSERT INTO smslog (
					mobileno
                    , message
                    , response
                    , proposedindentid
                    , customerno
                    , issmssent
                    , inserted_datetime
                    ) 
		VALUES (
					mobilenoparam
					, messageparam
					, responseparam
					, proposedindentidparam
                    , customernoparam
                    , smssentparam
					, todaysdate
                );
SET smsid = LAST_INSERT_ID();
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS get_skuweight$$
CREATE PROCEDURE get_skuweight(
    IN custno INT
    , IN did INT
    , IN factid INT
    , IN datereq datetime
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
        SET custno = NULL;
    END IF;
    IF(did = '' OR did = 0) THEN
        SET did = NULL;
    END IF;
    IF(factid = '' OR factid = 0) THEN
        SET factid = NULL;
    END IF;
    IF(datereq = '' OR datereq LIKE '%0000-00-00%') THEN
        SET datereq = NULL;
    END IF;
    SELECT  fd.skuid
			, s.skucode
			, s.sku_description
			, s.skutypeid
			, (s.weight * (COALESCE(s.netgross,0))) as unitweight
            , s.volume  as unitvolume
            , sum(fd.grossWeight) as skuweight
            , fd.date_required
            , fd.depotid
            , fd.factoryid
    FROM     factory_delivery fd INNER JOIN sku s ON fd.skuid = s.skuid
    WHERE    (fd.customerno = custno OR custno IS NULL)
    AND      (fd.factoryid = factid OR factid IS NULL)
    AND      (fd.depotid = did OR did IS NULL)
    AND      (fd.date_required = datereq OR datereq IS NULL)
    AND      (fd.isProcessed=0)
    GROUP BY date_required,factoryid, depotid, skuid;

END$$
DELIMITER ;

update transporter_actualshare SET shared_weight='0.0', total_weight='0.0';

update transporter_actualshare tas 
INNER JOIN transportershare ts ON     ts.transporterid = tas.transporterid
                               AND     ts.factoryid = tas.factoryid
                              AND    ts.zoneid    =    tas.zoneid
                              AND     ts.customerno = tas.customerno
                              AND    ts.isdeleted = 0
SET    tas.actualpercent = ts.sharepercent
WHERE    tas.actualpercent != ts.sharepercent;
-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 2, NOW(), 'Shrikant Suryawanshi','Changes For Indent View');



