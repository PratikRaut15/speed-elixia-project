DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_indent`$$
CREATE PROCEDURE `insert_indent`( 
	IN transporterid int
	, IN vehicleno varchar(25)
	, IN proposedindentid int
	, IN proposed_vehicletypeid INT
	, IN actual_vehicletypeid INT
	, IN factoryid INT
	, IN depotid INT
	, IN date_required date
	
	
	, IN totalweight decimal(7,3)
	, IN totalvolume decimal(7,3)
	
	, IN customerno INT
	, IN todaysdate DATETIME
    , IN userid INT
    , OUT currentindentid INT
)
BEGIN

	INSERT INTO indent	( 
							transporterid
							, vehicleno
							, proposedindentid
							,proposed_vehicletypeid
							,actual_vehicletypeid
							, factoryid
							, depotid
							,date_required
							
							
							, totalweight
							, totalvolume
							
							, customerno
							, created_on
							, updated_on
                            , created_by
                            , updated_by
						) 
	VALUES 	( 
				transporterid                
                , vehicleno
                , proposedindentid
                ,proposed_vehicletypeid
		,actual_vehicletypeid
		, factoryid
		, depotid
		,date_required
		
                , totalweight
		, totalvolume
		
                , customerno
                , todaysdate
                , todaysdate
                , userid
                , userid
			);

	SET currentindentid = LAST_INSERT_ID();

END$$
DELIMITER ;
