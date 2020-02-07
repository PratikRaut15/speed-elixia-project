
DELIMITER $$
DROP PROCEDURE IF EXISTS get_transporter_proposed_indent_detail$$
CREATE PROCEDURE get_transporter_proposed_indent_detail(
	IN custno INT
	, IN propindentid INT
	, IN transid INT
	, IN pitidparam INT
	, IN startdate DATE
	, IN enddate DATE
    , IN pageIndex INT
	, IN pageSize INT
    , IN searchstring VARCHAR(40)
    , OUT recordCount INT
    
)
BEGIN
	DECLARE fromRowNum INT DEFAULT 1;
	DECLARE toRowNum INT DEFAULT 1;
	SET fromRowNum = (pageIndex - 1) * pageSize + 1;
	SET toRowNum = (fromRowNum + pageSize) - 1;
	SET @rownum=0;
    SET searchstring = LTRIM(RTRIM(searchstring));
    IF searchstring = '' THEN
		SET searchstring = NULL;
	END IF;

	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
	IF(propindentid = '' OR propindentid = 0) THEN
		SET propindentid = NULL;
	END IF;
    	IF(transid = '' OR transid = 0) THEN
		SET transid = NULL;
	END IF;
    	IF(pitidparam = '' OR pitidparam = 0) THEN
		SET pitidparam = NULL;
	END IF;
    IF(startdate = '' OR startdate = 0) THEN
		SET startdate = NULL;
    END IF;
    IF(enddate = '' OR enddate = 0) THEN
		SET enddate = NULL;
    END IF;
    
    SET recordCount = (SELECT count(pit.proposedindentid)	        
		FROM 	proposed_indent_transporter_mapping pit
    	INNER JOIN proposed_indent pi ON pi.proposedindentid = pit.proposedindentid
    	INNER JOIN transporter t ON t.transporterid = pit.proposed_transporterid
    	INNER JOIN vehicletype vehtype ON vehtype.vehicletypeid = pit.proposed_vehicletypeid
    	left JOIN vehicletype veh ON veh.vehicletypeid = pit.actual_vehicletypeid
    	INNER join depot ON depot.depotid = pi.depotid 
    	INNER join factory ON factory.factoryid = pi.factoryid
		WHERE 	(pit.customerno = custno OR custno IS NULL)
		AND	(pit.proposedindentid = propindentid OR propindentid IS NULL)
    	AND	(pit.proposed_transporterid = transid OR transid IS NULL)
    	AND	(pit.pitmappingid = pitidparam OR pitidparam IS NULL)
        AND (pi.date_required BETWEEN startdate AND enddate 
        OR (startdate IS NULL AND enddate IS NULL))
        AND (pit.proposedindentid LIKE CONCAT('%', searchstring ,'%') OR searchstring IS NULL)
		AND pit.isdeleted = 0
		AND pit.isAccepted = 0
        AND pit.isAutoRejected = 0
	);
    
    /* If pageSize is -1, it means we need to give all the records in a single page */
	IF (pageSize = -1) THEN
		SET pageSize = recordCount;
    END IF;
    
	SET fromRowNum = (pageIndex - 1) * pageSize + 1;
	SET toRowNum = (fromRowNum + pageSize) - 1;
	SET @rownum=0;
    
    SELECT 
	rownum
    , pitmappingid	
			, proposedindentid
			, proposed_transporterid
			, hasTransporterAccepted
			, transportername
			, proposed_vehicletypeid
			, actual_vehicletypeid
            , proposedvehicledescription
			, proposedvehiclecode
            , skutypeid
            , actualvehicledescription
			, actualvehiclecode
			, date_required
			, depotid
			, depotname
			, factoryid
			, factoryname
			, vehicleno
			, drivermobileno
		    , isAccepted
            , isAutoRejected
			, customerno
			, created_on
			, updated_on 
			, created_by
			, updated_by
    FROM
(SELECT @rownum:=@rownum + 1 AS rownum
			, pit.pitmappingid	
			, pit.proposedindentid
			, pit.proposed_transporterid
			, pi.hasTransporterAccepted
			, t.transportername
			, pit.proposed_vehicletypeid
			, pit.actual_vehicletypeid
            , vehtype.vehicledescription as proposedvehicledescription
			, vehtype.vehiclecode as proposedvehiclecode
            , vehtype.skutypeid
            , veh.vehicledescription as actualvehicledescription
			, veh.vehiclecode as actualvehiclecode
			, pi.date_required
			, depot.depotid
			, depot.depotname
			, factory.factoryid
			, factory.factoryname
			, pit.vehicleno
			, pit.drivermobileno
		    , pit.isAccepted
            , pit.isAutoRejected
			, pit.customerno
			, pit.created_on
			, pit.updated_on 
			, pit.created_by
			, pit.updated_by
        FROM 	proposed_indent_transporter_mapping pit
    	INNER JOIN proposed_indent pi ON pi.proposedindentid = pit.proposedindentid
    	INNER JOIN transporter t ON t.transporterid = pit.proposed_transporterid
    	INNER JOIN vehicletype vehtype ON vehtype.vehicletypeid = pit.proposed_vehicletypeid
    	left JOIN vehicletype veh ON veh.vehicletypeid = pit.actual_vehicletypeid
    	INNER join depot ON depot.depotid = pi.depotid 
    	INNER join factory ON factory.factoryid = pi.factoryid
		WHERE 	(pit.customerno = custno OR custno IS NULL)
		AND	(pit.proposedindentid = propindentid OR propindentid IS NULL)
    	AND	(pit.proposed_transporterid = transid OR transid IS NULL)
    	AND	(pit.pitmappingid = pitidparam OR pitidparam IS NULL)
        AND (pi.date_required BETWEEN startdate AND enddate 
        OR (startdate IS NULL AND enddate IS NULL))
        AND (pit.proposedindentid LIKE CONCAT('%', searchstring ,'%') OR searchstring IS NULL)
		AND pit.isdeleted = 0
        AND pit.isAccepted = 0
        AND pit.isAutoRejected = 0
) allProposedIndents
WHERE	rownum BETWEEN fromRowNum AND toRowNum;

END$$
DELIMITER ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 14, NOW(), 'Shrikant Suryawanshi','Transporter APP API');
