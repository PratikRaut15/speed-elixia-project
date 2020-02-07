SET @patchId = 738;
SET @patchDate = '2019-12-20 12:05:00';
SET @patchOwner = 'Sanjeet Shukla';
SET @patchDescription = 'SP change - listener_update_daily_report';


INSERT INTO dbpatches(patchid, patchdate, appliedby, patchdesc, isapplied)
VALUES (@patchId, @patchDate, @patchOwner, @patchDescription, '0');

DELIMITER $$
DROP PROCEDURE IF EXISTS `listener_update_dailyreport`$$
CREATE PROCEDURE `listener_update_dailyreport`(
        last_online_updatedParam DATETIME
        , topspeedParam INT
        , topspeed_latParam DECIMAL(9,6)
        , topspeed_longParam DECIMAL(9,6)
        , topspeed_timeParam DATETIME
        , offline_data_timeParam INT
        , harsh_breakParam INT
        , flag_harsh_breakParam TINYINT(1)
        , sudden_accParam INT
        , flag_sudden_accParam TINYINT(1)
        , towingParam INT
        , flag_towingParam TINYINT(1)
        , odometerParam BIGINT
        , max_odometerParam BIGINT
        , first_odometerParam BIGINT
        , end_latParam DECIMAL(9,6)
        , end_longParam DECIMAL(9,6)
        , driveridParam INT
        , daily_dateParam DATE
        , vehicleidParam INT
        , customernoParam INT
        , OUT isUpdated TINYINT(1)
)
BEGIN
        BEGIN
            ROLLBACK;
            /*
            GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error;
            */
            SET isUpdated = 0;
        END;
        START TRANSACTION;
            UPDATE dailyreport 
            SET last_online_updated = last_online_updatedParam
                , topspeed = topspeedParam
                , topspeed_lat = topspeed_latParam
                , topspeed_long = topspeed_longParam
                , topspeed_time = topspeed_timeParam
                , offline_data_time = offline_data_timeParam
                , harsh_break = harsh_breakParam
                , flag_harsh_break = flag_harsh_breakParam
                , sudden_acc = sudden_accParam
                , flag_sudden_acc = flag_sudden_accParam
                , towing = towingParam
                , flag_towing = flag_towingParam
                , last_odometer = odometerParam
                , max_odometer = max_odometerParam
                , first_odometer = first_odometerParam
                , end_lat = end_latParam
                , end_long = end_longParam
                , driverid = driveridParam
            WHERE vehicleid = vehicleidParam 
            AND customerno = customernoParam 
            AND daily_date = daily_dateParam;
            SET isUpdated = 1;
        COMMIT;
END$$
DELIMITER ;

UPDATE dbpatches SET isapplied = 1, updatedOn =  DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE) WHERE patchid = @patchId;
