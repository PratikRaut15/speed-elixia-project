
DELIMITER $$
DROP PROCEDURE IF EXISTS `editBucketOperation`$$
CREATE PROCEDURE `editBucketOperation`(
      IN statusParam INT(11)
     ,IN bucketidParam INT(11)
     ,IN dataParam VARCHAR(100)
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

    START TRANSACTION;	 
    BEGIN
        IF statusParam = 4 THEN

            UPDATE  bucket 
            SET     fe_id= dataParam
                    ,status=statusParam
                    ,fe_assigned_timestamp = todaysdateParam 
            where   bucketid= bucketidParam;

        END IF;

        IF statusParam = 5 THEN

            UPDATE  bucket 
            SET     status=statusParam 
                    ,cancelled_timestamp = todaysdateParam 
                    , cancellation_reason= dataParam 
            where   bucketid=bucketidParam;

        END IF;

        IF statusParam = 1 THEN

            UPDATE  bucket 
            SET     status =statusParam
                    , reschedule_date=dataParam
                    ,reschedule_timestamp = todaysdateParam 
            where   bucketid=bucketidParam;

            INSERT INTO bucket(`apt_date`
                    ,`customerno`
                    ,`created_by`
                    , `priority`
                    , `vehicleid`
                    , `location`
                    , `timeslotid`
                    , `purposeid`
                    , `details`
                    , `coordinatorid`
                    , `create_timestamp`
                    , `status`)
            SELECT   dataParam
                    ,`customerno`
                    ,`created_by`
                    , `priority`
                    , `vehicleid`
                    , `location`
                    , `timeslotid`
                    , `purposeid`
                    , `details`
                    , `coordinatorid`
                    , todaysdateParam
                    , 0
            FROM    bucket 
            WHERE   bucketid=bucketidParam
            ORDER BY `bucketid` DESC
            LIMIT   1;

        END IF;

        SET isexecutedOut = 1;

    END;
    COMMIT;

END;

END$$
DELIMITER ; 