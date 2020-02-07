    
DELIMITER $$
DROP PROCEDURE IF EXISTS `editBucketCRM`$$
CREATE PROCEDURE `editBucketCRM`(
     IN statusParam TINYINT(2)
    ,IN customernoParam INT(11)
    ,IN vehicleidParam INT(11)
    ,IN createdbyParam TINYINT(3)
    ,IN priorityidParam TINYINT(2)
    ,IN locationParam VARCHAR(50)
    ,IN timeslotParam TINYINT(2)
    ,IN purposeidParam TINYINT(2)
    ,IN detailsParam VARCHAR(100)
    ,IN dataParam VARCHAR(100)
    ,IN coordinatorParam INT(11)
    ,IN aptdateParam DATETIME
    ,IN conameParam VARCHAR(50)
    ,IN cophoneParam INT(11)
    ,IN bucketidParam INT(11)
    ,IN todaysdateParam DATETIME
    ,OUT isexecutedOut TINYINT(2))
BEGIN

DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
            ROLLBACK;
            /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error;   */
            SET isexecutedOut = 0;
	END;
    BEGIN 
    
    SET isexecutedOut = 0;

    START TRANSACTION;	 
    BEGIN

        IF conameParam <> '' THEN

            INSERT INTO contactperson_details (`typeid` 
                    ,`person_name`
                    ,`cp_phone1`
                    , `customerno`
                    , `insertedby`
                    , `insertedon`)
            VALUES (3
                    ,conameParam
                    , cophoneParam
                    , customernoParam
                    , createdbyParam
                    , todaysdateParam);

            SELECT LAST_INSERT_ID() INTO coordinatorParam;

        END IF;

        IF statusParam = 0 THEN

            UPDATE  bucket 
            SET     apt_date = aptdateParam 
                    , coordinatorid = coordinatorParam
                    , priority = priorityidParam
                    , location = locationParam
                    , timeslotid = timeslotParam
                    , purposeid = purposeidParam
                    , details = detailsParam
                    , status = statusParam
                    , create_timestamp = todaysdateParam 
            where   bucketid = bucketidParam;

        END IF;

        IF statusParam = 5 THEN

            UPDATE  bucket 
            SET     status=statusParam
                    , cancelled_timestamp = todaysdateParam
                    , cancellation_reason = dataParam 
            where   bucketid=bucketidParam;

        END IF;

        IF statusParam = 1 THEN

            UPDATE  bucket 
            SET     status= statusParam
                    , reschedule_date= dataParam
                    , reschedule_timestamp = todaysdateParam 
            where   bucketid = bucketidParam;


            INSERT INTO bucket (`apt_date`
                ,`customerno`
                ,`created_by`
                , `priority`
                , `vehicleid`
                , `location`
                , `timeslotid`
                , `purposeid`
                , `details`
                , `coordinatorid`
                , `create_timestamp`, status)
            VALUES (dataParam,customernoParam
                ,createdbyParam
                , priorityidParam
                , vehicleidParam
                ,locationParam
                ,timeslotParam
                ,purposeidParam
                ,detailsParam
                ,coordinatorParam
                ,todaysdateParam
                ,0);

        END IF;
        
        SET isexecutedOut = 1;
    END;
    COMMIT;
    
END;
END$$
DELIMITER ; 