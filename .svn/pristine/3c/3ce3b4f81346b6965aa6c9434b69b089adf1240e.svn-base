DROP TABLE IF EXISTS `chalaan`;
CREATE TABLE IF NOT EXISTS `chalaan` (
`chalid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `chalaan_no` varchar(20) NOT NULL,
  `chalaan_date` date DEFAULT NULL,
  `vendor_invno` varchar(20) NOT NULL,
  `vendor_invdate` date DEFAULT NULL,
  `insertedby` int(11) NOT NULL,
  `insertedon` datetime DEFAULT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL
);

ALTER TABLE `chalaan`
 ADD PRIMARY KEY (`chalid`);

ALTER TABLE `chalaan`
MODIFY `chalid` int(11) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `ledger`;
CREATE TABLE IF NOT EXISTS `ledger` (
`ledgerid` int(11) NOT NULL,
  `ledgername` varchar(100) NOT NULL,
  `address1` varchar(100) NOT NULL,
  `address2` varchar(100) NOT NULL,
  `address3` varchar(100) NOT NULL,
  `email` varchar(40) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `pan_no` varchar(30) NOT NULL,
  `cst_no` varchar(30) NOT NULL,
  `st_no` varchar(30) NOT NULL,
  `vat_no` varchar(30) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
);


ALTER TABLE `ledger`
 ADD PRIMARY KEY (`ledgerid`);

ALTER TABLE `ledger`
MODIFY `ledgerid` int(11) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `ledger_cust_mapping`;
CREATE TABLE IF NOT EXISTS `ledger_cust_mapping` (
`ledger_cust_mapid` int(11) NOT NULL,
  `ledgerid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
);


ALTER TABLE `ledger_cust_mapping`
 ADD PRIMARY KEY (`ledger_cust_mapid`);


ALTER TABLE `ledger_cust_mapping`
MODIFY `ledger_cust_mapid` int(11) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `ledger_veh_mapping`;
CREATE TABLE IF NOT EXISTS `ledger_veh_mapping` (
`ledger_veh_mapid` int(11) NOT NULL,
  `ledgerid` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
);


ALTER TABLE `ledger_veh_mapping`
 ADD PRIMARY KEY (`ledger_veh_mapid`);


ALTER TABLE `ledger_veh_mapping`
MODIFY `ledger_veh_mapid` int(11) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `po`;
CREATE TABLE IF NOT EXISTS `po` (
`poid` int(11) NOT NULL,
  `pono` varchar(30) NOT NULL,
  `podate` date NOT NULL,
  `poamount` int(11) NOT NULL,
  `poexpiry` date NOT NULL,
  `description` varchar(50) NOT NULL,
  `total_devices` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
);

ALTER TABLE `po`
 ADD PRIMARY KEY (`poid`);

ALTER TABLE `po`
MODIFY `poid` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `transmitter`  ADD `trans_status` INT(11) NOT NULL  AFTER `teamid`;
ALTER TABLE `transmitter`  ADD `comments` VARCHAR(50) NOT NULL  AFTER `trans_status`;
ALTER TABLE `invoice`  ADD `inv_expiry` DATE NOT NULL  AFTER `unpaid_amt`;


/*procedures */

/* PO */
DELIMITER $$
DROP PROCEDURE IF EXISTS `get_po`$$
CREATE PROCEDURE `get_po`( 
IN customernoparam INT
,IN poidparam INT
)

BEGIN
IF(customernoparam = '' OR customernoparam = '0') THEN
 SET customernoparam = NULL;
END IF;
IF(poidparam = '' OR poidparam = '0') THEN
 SET poidparam = NULL;
END IF;
SELECT
	poid
    ,pono
    ,podate
    ,poamount
    ,poexpiry
    ,description
    ,customerno
    ,createdby
    ,createdon
    ,updatedby
    ,updatedon
FROM po
WHERE 
(customerno = customernoparam OR customernoparam IS NULL)
AND (poid = poidparam OR poidparam IS NULL)
AND isdeleted = 0
;
END$$
DELIMITER ;

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


DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_po`$$
CREATE  PROCEDURE `delete_po`(
	IN poidparam INT
    , IN custparam INT
    , IN updatedby INT
    , IN updatedon DATETIME
	)
BEGIN
UPDATE po
SET isdeleted = 1
 ,updatedby = updatedby
 ,updatedon = updatedon
WHERE poid  =  poidparam
AND customerno = custparam
;
END$$
DELIMITER ;

/* transmitters */

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_transmitter`$$
CREATE PROCEDURE `get_transmitter`( 
    IN transmitteridparam INT
    , IN transmitternoparam VARCHAR(25)
    , IN teamidparam VARCHAR(20)
    , IN trans_statusparam VARCHAR(20)
    , IN customernoparam VARCHAR(20)
)
BEGIN
	IF(transmitteridparam = '' OR transmitteridparam = '0') THEN
		SET transmitteridparam = NULL;
	END IF;

	IF(customernoparam = '') THEN
		SET customernoparam = NULL;
	 ELSE 
		SET customernoparam = CAST(customernoparam AS SIGNED INTEGER);
	END IF;

	IF(trans_statusparam = '') THEN
	 SET trans_statusparam = NULL;
	END IF;

	IF(transmitternoparam = '') THEN
	 SET transmitternoparam = NULL;
	END IF;

	IF(teamidparam = '') THEN
		SET teamidparam = NULL;
	 ELSE 
		SET teamidparam = CAST(teamidparam AS SIGNED INTEGER);
	END IF;

	SELECT t.transmitterid
			,t.transmitterno
			,t.teamid
			,t.comments
			,t.customerno
			,t.trans_status
			,t.created_on
			,t.updated_on
			,t.created_by
			,t.updated_by
			,ts.`status`
	FROM  transmitter as t
	INNER JOIN trans_status AS ts ON ts.id = t.trans_status
	WHERE (t.transmitterno  = transmitteridparam OR transmitteridparam IS NULL)
	AND (t.transmitterno = transmitternoparam OR transmitternoparam IS NULL)
	AND (t.teamid = teamidparam OR teamidparam IS NULL)
	AND (t.customerno IN (customernoparam) OR customernoparam IS NULL)
	AND (FIND_IN_SET(t.trans_status,trans_statusparam) OR trans_statusparam IS NULL)
	AND t.isdeleted = 0
	ORDER BY t.transmitterno ASC
;        
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_transmitter`$$
CREATE PROCEDURE `insert_transmitter`( 
    IN transmitterno VARCHAR(25)
    , IN teamid INT
    , IN customerno INT
    , IN trans_status INT
    , IN created_on DATETIME
    , IN created_by INT
    , IN comments VARCHAR(50)
)
BEGIN
INSERT INTO transmitter(
							transmitterno
                            ,teamid
                            ,comments
                            ,customerno
                            ,trans_status
                            ,created_on
                            ,updated_on
                            ,created_by
                            ,updated_by
                            
						)
                        VALUES(
							transmitterno
                            ,teamid
                            ,comments
                            ,customerno
                            ,trans_status
                            ,created_on
                            ,created_on
                            ,created_by
                            ,created_by
                        )
        ;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `update_transmitter`$$
CREATE PROCEDURE `update_transmitter`( 
    IN transmitteridparam INT
    , IN teamid INT
    , IN trans_status INT
    , IN comments VARCHAR(50)
    , IN customernoparam INT
    , IN updated_on DATETIME
    , IN updated_by INT
)
BEGIN
 UPDATE transmitter SET 	
            teamid = teamid
            ,comments = comments
            ,trans_status = trans_status
            ,updated_on = updated_on
            ,updated_by = updated_by
            ,customerno = customernoparam
  WHERE      transmitterid = transmitteridparam 
	
        ;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_transmitter`$$
CREATE PROCEDURE `delete_transmitter`( 
    IN transmitteridparam INT
    , IN customernoparam INT
    , IN updated_on DATETIME
    , IN updated_by INT
)
BEGIN
 UPDATE transmitter SET 
			isdeleted = 1
            ,updated_on = updated_on
            ,updated_by = updated_by
  WHERE      transmitterid = transmitteridparam 
	AND 	customerno = customernoparam
        ;
END$$
DELIMITER ;

/* ledger */

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
)
BEGIN
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
END$$
DELIMITER ;

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
ORDER BY l.updatedon DESC
;     
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `update_ledger`$$
CREATE PROCEDURE `update_ledger`( 
	IN ledgeridparam INT
    , IN ledgername VARCHAR(100)
    , IN address1 VARCHAR(100)
    , IN address2 VARCHAR(100)
    , IN address3 VARCHAR(100)
    , IN email VARCHAR(40)
    , IN phone VARCHAR(20)
    , IN pan_no VARCHAR(30)
    , IN cst_no VARCHAR(30)
    , IN st_no VARCHAR(30)
    , IN vat_no VARCHAR(30)
    , IN updatedby INT
    , IN updatedon DATETIME
)
BEGIN
UPDATE ledger SET 
	ledgername = ledgername
    , address1 = address1 
    , address2 = address2
    , address3 = address3
    , email = email
    , phone = phone 
    , pan_no = pan_no
    , cst_no = cst_no
    , st_no = st_no
    , vat_no = vat_no
    , updatedby = updatedby
	, updatedon = updatedon
    WHERE ledgerid = ledgeridparam AND isdeleted = 0
    ;
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
END$$
DELIMITER ;

/*ledger_cust_mapping */

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_ledger_cust_mapping`$$
CREATE PROCEDURE `get_ledger_cust_mapping`( 
    IN ledgeridparam INT
    ,IN customernoparam INT
    , IN ledgernameparam VARCHAR(100)
)
BEGIN
	IF(ledgeridparam = '' OR ledgeridparam = '0') THEN
		SET ledgeridparam = NULL;
	END IF;

	IF(ledgernameparam = '' OR ledgernameparam = '0') THEN
		SET ledgernameparam = NULL;
	END IF;
    
    IF(customernoparam = '' OR customernoparam = '0') THEN
		SET customernoparam = NULL;
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
        ,lc.customerno
FROM ledger_cust_mapping AS lc
INNER JOIN ledger AS l ON l.ledgerid = lc.ledgerid
WHERE (lc.ledgerid  = ledgeridparam OR ledgeridparam IS NULL)
AND (lc.customerno  = customernoparam OR customernoparam IS NULL)
AND (l.ledgername LIKE CONCAT('%', ledgernameparam, '%') OR ledgernameparam IS NULL)
AND lc.isdeleted = 0
;     
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_ledger_cust_mapping`$$
CREATE PROCEDURE `insert_ledger_cust_mapping`(
	IN ledgerid INT
    , IN customerno INT
    , IN createdby INT
    , IN createdon DATETIME
    , IN updatedby INT
    , IN updatedon DATETIME
)
BEGIN
INSERT INTO ledger_cust_mapping
(
	ledgerid
    ,customerno
    , createdby 
    , createdon 
    , updatedby 
	, updatedon 
) VALUES
(
	ledgerid
    ,customerno
    , createdby 
    , createdon 
    , updatedby 
	, updatedon 
)
    ;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_ledger_cust_mapping`$$
CREATE PROCEDURE `delete_ledger_cust_mapping`( 
	IN ledgeridparam INT
	, IN updatedby INT
    , IN updatedon DATETIME
)
BEGIN
	UPDATE ledger_cust_mapping SET 
    isdeleted = 1
    ,updatedby = updatedby
    ,updatedon = updatedon
    WHERE ledgerid = ledgeridparam
    ;
END$$
DELIMITER ;

/* ledger_veh_mapping */

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_ledger_veh_mapping`$$
CREATE PROCEDURE `get_ledger_veh_mapping`( 
    IN ledger_veh_mapidparam INT
    , IN customernoparam INT
    , IN ledgeridparam INT
    , IN vehiclenoparam VARCHAR(20)
)
BEGIN

IF(ledger_veh_mapidparam = '' OR ledger_veh_mapidparam = '0') THEN
 SET ledger_veh_mapidparam = NULL;
END IF;

IF(customernoparam = '' OR customernoparam = '0') THEN
 SET customernoparam = NULL;
END IF;

IF(vehiclenoparam = '' OR vehiclenoparam = '0') THEN
 SET vehiclenoparam = NULL;
END IF;

IF(ledgeridparam = '' OR ledgeridparam = '0') THEN
 SET ledgeridparam = NULL;
END IF;

SELECT 	l.ledger_veh_mapid
		,l.ledgerid
		,l.vehicleid
        ,l.customerno
        ,v.vehicleno
		,l.createdby
        ,l.createdon
        ,l.updatedby
        ,l.updatedon
FROM ledger_veh_mapping as l
INNER JOIN vehicle as v ON l.vehicleid = v.vehicleid
WHERE (l.ledger_veh_mapid  = ledger_veh_mapidparam OR ledger_veh_mapidparam IS NULL)
AND (l.customerno = customernoparam OR customernoparam IS NULL)
AND (l.ledgerid = ledgeridparam OR ledgeridparam IS NULL)
AND (v.vehicleno LIKE CONCAT('%', vehiclenoparam, '%') OR vehiclenoparam IS NULL)
AND l.isdeleted = 0
ORDER BY v.vehicleno ASC
;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_ledger_veh_mapping`$$
CREATE PROCEDURE `insert_ledger_veh_mapping`( 
    IN vehicleid INT
    , IN ledgerid INT
	, IN customerno INT
    , IN createdby INT
    , IN createdon DATETIME
    , IN updatedby INT
    , IN updatedon DATETIME
)
BEGIN
INSERT INTO ledger_veh_mapping
(
	ledgerid
    ,vehicleid
    ,customerno
    , createdby 
    , createdon 
    , updatedby 
	, updatedon 
) VALUES
(
	ledgerid
    ,vehicleid
    ,customerno
    , createdby 
    , createdon 
    , updatedby 
	, updatedon 
)
    ;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_ledger_veh_mapping`$$
CREATE PROCEDURE `delete_ledger_veh_mapping`( 
    IN ledger_veh_mapidparam INT
    , IN customernoparam INT
    , IN ledgeridparam INT
    , IN updatedby INT
    , IN updatedon DATETIME
)
BEGIN
IF(ledger_veh_mapidparam = '' OR ledger_veh_mapidparam = '0') THEN
 SET ledger_veh_mapidparam = NULL;
END IF;

IF(customernoparam = '' OR customernoparam = '0') THEN
 SET customernoparam = NULL;
END IF;

IF(ledgeridparam = '' OR ledgeridparam = '0') THEN
 SET ledgeridparam = NULL;
END IF;
UPDATE 
 ledger_veh_mapping as lv
 SET lv.isdeleted = 1
 ,lv.updatedby = updatedby
 ,lv.updatedon = updatedon
WHERE (lv.ledger_veh_mapid  = ledger_veh_mapidparam OR ledger_veh_mapidparam IS NULL)
AND (lv.customerno = customernoparam OR customernoparam IS NULL)
AND (lv.ledgerid = ledgeridparam OR ledgeridparam IS NULL)
;
END$$
DELIMITER ;

-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
		, isapplied =1 
WHERE 	patchid = 379;



