DELIMITER $$
DROP PROCEDURE IF EXISTS update_proposed_indent$$
CREATE PROCEDURE `update_proposed_indent`( 
	IN propindentid int
	, IN factoryid int
    , IN depotid int
	, IN total_weight decimal(7,3)
	, IN total_volume decimal(7,3)
	, IN isApprovedParam TINYINT(1)
    , IN hasTransAccepted TINYINT(1)
	, IN todaysdate DATETIME
    , IN userid INT
)
BEGIN

	IF	((propindentid IS NOT NULL && propindentid != '' && propindentid != '0')
		AND (factoryid IS NOT NULL && factoryid !='' && factoryid !='0')
		AND (depotid IS NOT NULL && depotid !='' && depotid !='0')
        AND (total_weight IS NOT NULL && total_weight !='')
        AND (total_volume IS NOT NULL && total_volume != '')) THEN
			UPDATE 	proposed_indent
			SET 	factoryid = factoryid
					, depotid = depotid
					, total_weight = total_weight
					, total_volume = total_volume
                    , updated_on = todaysdate 
					, updated_by = userid
			WHERE	proposedindentid = propindentid;
    ELSEIF (hasTransAccepted IS NOT NULL) THEN 
			UPDATE 	proposed_indent
			SET 	hasTransporterAccepted = hasTransAccepted
					, updated_on = todaysdate 
					, updated_by = userid
			WHERE proposedindentid = propindentid;
    END IF;        
	IF (isApprovedParam IS NOT NULL) THEN
			UPDATE 	proposed_indent
			SET 	 isApproved = isApprovedParam
					, updated_on = todaysdate 
					, updated_by = userid
			WHERE	proposedindentid = propindentid;
            
	END IF ;

END$$
DELIMITER ;