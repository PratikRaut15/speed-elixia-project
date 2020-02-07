/*
	Name			- get_indent
    Description 	-	To get indents
    Parameters		-	customernoparam, indentid, factoryid, startdate, enddate
    Module			-TMS
    Sub-Modules 	- 	Indents
    Sample Call		-	CALL get_indent('116','','','2015-12-01','2015-12-15');

    Created by		-	Shrikant 
    Created	on		- Nov, 2015
    Change details 	-
    1) 	Updated by	-	Shrikant Suryawanshi
	Updated	on	-	17 Dec 2015 
        Reason		-	Add startdate, enddate for filtering data
    2) 
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS get_indent$$
CREATE PROCEDURE get_indent(
	IN custno INT
    , IN indentidparam INT
    , IN factoryidparam INT
    , IN startdate DATE
	, IN enddate DATE
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
    IF(startdate = '' OR startdate = 0) THEN
		SET startdate = NULL;
    END IF;
    IF(enddate = '' OR enddate = 0) THEN
		SET enddate = NULL;
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
        AND (i.date_required BETWEEN startdate AND enddate 
        OR (startdate IS NULL AND enddate IS NULL))
        AND i.isdeleted = 0;
END$$
DELIMITER ;
