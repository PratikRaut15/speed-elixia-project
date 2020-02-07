DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_pit_mapping`$$
CREATE  PROCEDURE `insert_pit_mapping`( 
	IN proposedindentid int,
	IN proposed_transporterid int,
    IN proposed_vehicletypeid INT,
	IN customerno INT
	, IN todaysdate DATETIME
    , IN userid INT
    , OUT currentpitmappingid INT
)
BEGIN

	INSERT INTO proposed_indent_transporter_mapping(
								proposedindentid 
								,proposed_transporterid
                                , proposed_vehicletypeid
								,customerno
								, created_on
								, updated_on
								, created_by
								, updated_by
                            ) 
	VALUES ( 
				proposedindentid
                , proposed_transporterid
		, proposed_vehicletypeid
                , customerno
                , todaysdate
                , todaysdate
                , userid
                , userid
			);
	SET currentpitmappingid = LAST_INSERT_ID();

END$$
DELIMITER ;
