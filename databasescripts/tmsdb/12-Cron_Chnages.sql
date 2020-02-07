
DELIMITER $$
DROP PROCEDURE IF EXISTS get_transporter_indents$$ 
CREATE PROCEDURE get_transporter_indents(
	IN custno INT,
	IN transporteridparam INT
)
BEGIN
	
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
	IF(transporteridparam = '' OR transporteridparam = 0) THEN
		SET transporteridparam = NULL;
	END IF;
    
    	SELECT 
		pi.proposedindentid
		, pi.factoryid
		, f.factoryname
		, pi.depotid
		, d.depotname
		, pi.date_required
		, pit.proposed_vehicletypeid
		, pit.actual_vehicletypeid
		, v.vehiclecode as proposedvehiclecode
		, v.vehicledescription as proposedvehicledescription
		, veh.vehiclecode as actualvehiclecode
		, veh.vehicledescription as actualvehicledescription
        , pit.isAccepted
        , pit.isAutoRejected
        , t.transportername
        , pit.updated_on
	FROM proposed_indent_transporter_mapping as pit
    INNER JOIN proposed_indent as pi on pi.proposedindentid = pit.proposedindentid
    INNER JOIN transporter as t on t.transporterid = pit.proposed_transporterid
	INNER JOIN factory as f on f.factoryid = pi.factoryid
	INNER JOIN depot as d on d.depotid = pi.depotid
	INNER JOIN vehicletype as v on v.vehicletypeid = pit.proposed_vehicletypeid
	LEFT JOIN vehicletype as veh on veh.vehicletypeid = pit.actual_vehicletypeid
	WHERE (pit.customerno = custno OR custno IS NULL)
    AND (pit.proposed_transporterid = transporteridparam OR transporteridparam IS NULL)
	    AND pi.isdeleted=0
        AND pit.isdeleted=0
        order by f.factoryname; 
END$$
DELIMITER ;



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 12, NOW(), 'Shrikant Suryawanshi','Cron Changes');
