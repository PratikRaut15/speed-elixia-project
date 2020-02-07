DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_proposed_indent`$$
CREATE PROCEDURE `insert_proposed_indent`( 
	IN factoryid int
	,IN depotid int 
	, IN total_weight float(7,3)
	, IN total_volume float(7,3)
	, IN daterequired date
    , IN remark varchar(250)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT currentproposedindentid INT
)
BEGIN

	INSERT INTO proposed_indent( 
					factoryid
					, depotid	
                    , total_weight
                    , total_volume
					, date_required
                    , remark
                    , customerno
                    , created_on
                    , updated_on 
                    , created_by
                    , updated_by
                    ) 
	VALUES 	( 
				factoryid
				, depotid
                , total_weight
                , total_volume
				, daterequired
                , remark
                , customerno
                , todaysdate
                , todaysdate
                , userid
                , userid
			);

	SET currentproposedindentid = LAST_INSERT_ID();

	call update_factory_delivery(0,factoryid,0,depotid, daterequired,'',customerno,todaysdate,userid,1);

END$$
DELIMITER ;
