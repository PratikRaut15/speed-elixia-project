INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'612', '2018-09-19 11:10:00', 'Sanjeet Shukla', 'changes in freezevehicle Api (Added freezeRadiusInKm)', '0'
);


ALTER TABLE `freezelog` ADD `freezeRadiusInKm` FLOAT NOT NULL AFTER `devicelong`;


DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_freeze_log`$$
CREATE PROCEDURE `insert_freeze_log`(
      IN vehicleIdParam VARCHAR(50)
    , IN uIdParam INT
    , IN deviceLatParam FLOAT
    , IN deviceLongParam FLOAT
    , IN freezeRadiusInKmParam FLOAT
    , IN isApiParam INT
    , IN fStatusParam INT
    , IN userIdParam INT
    , IN customerNoParam INT
    , IN todaysDateParam DATETIME
    , OUT currentFreezeLogId INT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    SET currentFreezeLogId = 0;
    BEGIN
        ROLLBACK;
        
        /*GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;*/
        
    END;

    START TRANSACTION;

        IF (customerNoParam = '' OR customerNoParam = 0) THEN
            SET customerNoParam = NULL;
        END IF;

        IF (uIdParam = '' OR uIdParam = 0) THEN
            SET uIdParam = NULL;
        END IF;

/*select vehicleIdParam ,
    uIdParam ,
    deviceLatParam ,
    deviceLongParam ,
    freezeRadiusInKmParam ,
    isApiParam ,
    userIdParam ,
    customerNoParam ,
    todaysDateParam ,
     currentFreezeLogId;*/
     
        IF (customerNoParam IS NOT NULL AND uIdParam IS NOT NULL) THEN
			IF(fStatusParam = 1) THEN
				BEGIN
					UPDATE unit set is_freeze=1 where uid = uIdParam;
				END;
				BEGIN
					INSERT INTO freezelog (
							uid, 
							vehicleid, 
							devicelat,
							devicelong,
							freezeRadiusInKm,
							customerno,
							createdby,
							createdon,
							updatedby,
							updatedon,
							is_api
						)
					VALUES (
							uIdParam
							,vehicleIdParam
							,deviceLatParam
							,deviceLongParam
							,freezeRadiusInKmParam
							,customerNoParam
							,userIdParam
							,todaysDateParam
							,userIdParam
							,todaysDateParam
							,isApiParam
						);
					SET currentFreezeLogId = LAST_INSERT_ID();      
				 END;
			ELSEIF(fStatusParam = 0) THEN
            
				BEGIN
					UPDATE unit set is_freeze=0 where uid = uIdParam;
				END;
                
                UPDATE 	freezelog 
                set 	isdeleted=1,
						updatedon=todaysDateParam, 
                        updatedby= userIdParam 
                where 	uid= uIdParam AND isdeleted=0;
                
                SET currentFreezeLogId = 1;
                
			END IF;
		END IF;

        
    COMMIT;


END$$
DELIMITER ;

UPDATE  dbpatches
SET     updatedOn = NOW(),isapplied = 1
WHERE   patchid = 612;
