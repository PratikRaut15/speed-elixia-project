ALTER TABLE indent add loadstatus tinyint(1) DEFAULT 0 AFTER actual_deliverydate;
ALTER TABLE indent add remarks varchar(250) AFTER shipmentno;
ALTER TABLE `proposed_indent` ADD `remark` VARCHAR(250) NOT NULL AFTER `hasTransporterAccepted`;

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
    veh.vehiclecode AS actualvehiclecode,
    i.date_required,
    i.loadstatus,
    shipmentno,
    i.remarks,
    totalweight,
    totalvolume,
    isdelivered,
    ism.skuid,
    ism.no_of_units,
    s.sku_description,
    i.customerno
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
        LEFT JOIN
    indent_sku_mapping ism ON ism.indentid = i.indentid
        LEFT JOIN
    sku s ON s.skuid = ism.skuid
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
DROP PROCEDURE IF EXISTS edit_indent$$
CREATE PROCEDURE edit_indent( 
	IN indentidparam int,
	IN loadstatus int,
	IN shipmentno varchar(50),
	IN remark varchar(250)
	, IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE 	indent
	SET 	
			loadstatus = loadstatus
			,shipmentno = shipmentno
			,remarks = remark
			,updated_on = todaysdate
			,updated_by = userid
	WHERE 	indentid = indentidparam;

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS get_proposed_indent$$
CREATE PROCEDURE get_proposed_indent(
	IN custno INT
    , propindentid INT
    , factoryidparam INT
    , daterequired varchar(15)
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
        IF(propindentid = '' OR propindentid = 0) THEN
		SET propindentid = NULL;
	END IF;
        IF(factoryidparam = '' OR factoryidparam = 0) THEN
		SET factoryidparam = NULL;
	END IF;
        IF(daterequired = '' OR daterequired = 0) THEN
		SET daterequired = NULL;
	END IF;
	SELECT 
    pi.proposedindentid,
    pi.factoryid,
    pi.hasTransporterAccepted,
    pi.isApproved,
    f.factoryname,
    pi.depotid,
    d.depotname,
    t.transporterid,
    t.transportername,
    vehtype.vehiclecode,
    veh.vehiclecode AS actualvehiclecode,
    pit.vehicleno,
    pi.date_required,
    pit.proposed_vehicletypeid,
    pit.actual_vehicletypeid,
    pit.isAccepted,
    pit.vehicleno,
    pit.drivermobileno,
    total_weight,
    total_volume,
    pi.customerno,
    pi.created_on,
    pi.updated_on,
    pi.created_by,
    pi.updated_by,
    i.loadstatus,
    i.shipmentno,
    i.remarks
FROM
    proposed_indent pi
        INNER JOIN
    proposed_indent_transporter_mapping pit ON pit.proposedindentid = pi.proposedindentid
        INNER JOIN
    factory f ON f.factoryid = pi.factoryid
        INNER JOIN
    depot d ON d.depotid = pi.depotid
        INNER JOIN
    transporter t ON t.transporterid = pit.proposed_transporterid
        INNER JOIN
    vehicletype vehtype ON vehtype.vehicletypeid = pit.proposed_vehicletypeid
        LEFT JOIN
    vehicletype veh ON veh.vehicletypeid = pit.actual_vehicletypeid
        LEFT JOIN
    indent i ON i.proposedindentid = pi.proposedindentid
WHERE
    (pi.customerno = custno OR custno IS NULL)
        AND (pi.proposedindentid = propindentid
        OR propindentid IS NULL)
        AND (pi.factoryid = factoryidparam
        OR factoryidparam IS NULL)
        AND (pi.date_required = daterequired
        OR daterequired IS NULL)
        AND pi.isdeleted = 0;
END$$
DELIMITER ;

