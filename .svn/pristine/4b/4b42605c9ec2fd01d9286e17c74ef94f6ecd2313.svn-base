DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_factory`$$
CREATE  PROCEDURE `insert_factory`( 
	IN factorycode VARCHAR (10)	
	,IN factoryname VARCHAR (50)
    ,IN zoneid INT
	,IN customerno INT
    ,IN todaysdate DATETIME
    ,IN userid INT
    , OUT currentfactoryid INT
	)
BEGIN
	INSERT INTO factory (
							factorycode							
							,factoryname
							, zoneid
                            				, customerno
							, created_on
							, updated_on
							, created_by
							, updated_by
						)
	VALUES ( 
				factorycode
				,factoryname                		
				, zoneid
				, customerno
				, todaysdate
				, todaysdate
                		, userid
                		, userid
			);
            
	SET currentfactoryid = LAST_INSERT_ID();

END$$
DELIMITER ;
