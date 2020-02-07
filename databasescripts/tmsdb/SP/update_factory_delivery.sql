DELIMITER $$
DROP PROCEDURE IF EXISTS update_factory_delivery$$
CREATE PROCEDURE `update_factory_delivery`( 
	IN fdidparam int
	, IN factid int
	, IN skuidparam int
	, IN did int
	, IN daterequired date
	, IN weight decimal(7,3)
	, IN custno INT
	, IN todaysdate DATETIME
	, IN userid INT
	, IN isprocessed INT
)
BEGIN

	IF (isprocessed=1) THEN
		UPDATE 	factory_delivery 
		SET 	isProcessed = isprocessed 
		WHERE 	factoryid=factid 
		and 	depotid= did 
		and 	date_required = daterequired
		and     customerno = custno;
	ELSE	
		BEGIN
			DECLARE grosswt decimal(7,3);
	    		DECLARE tempnetgross decimal(5,2);
	    
	    		SELECT  netgross
			INTO 	tempnetgross
		    	FROM 	sku
		    	WHERE 	skuid = skuidparam
		    	AND 	customerno = custno;
	    
	    		SET grosswt = weight + (weight * tempnetgross);

			UPDATE 	factory_delivery
			SET 	factoryid=factid
					,skuid = skuidparam
					,depotid = did
					,date_required = daterequired
					,netWeight = weight
					,grossWeight = grosswt
					,updated_on = todaysdate 
					, updated_by = userid
			WHERE 	fdid = fdidparam;
		END;
	END IF;

END$$
DELIMITER ;
