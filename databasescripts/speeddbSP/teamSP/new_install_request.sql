

DELIMITER $$
DROP PROCEDURE IF EXISTS new_install_request$$
CREATE PROCEDURE new_install_request(
        IN todaysdateParam DATETIME
        ,IN aptDateParam DATE
        ,IN priorityParam INT(4)
        ,IN locationParam VARCHAR(50)
        ,IN timeslotParam INT(4)
        ,IN detailsParam VARCHAR(100)
        ,IN coordinatorParam INT(11)
        ,IN conameParam VARCHAR(30)
        ,IN cophoneParam VARCHAR(15)
        ,IN installCountParam TINYINT(2)
        ,IN customernoParam INT(11)
        ,IN lteamidParam INT(11)
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

        DECLARE counterVar INT UNSIGNED DEFAULT 0;
        
        START TRANSACTION;	 
        BEGIN

            IF conameParam <> '' AND conameParam IS NOT NULL THEN

                INSERT INTO contactperson_details (`typeid`
                        ,`person_name`
                        ,`cp_phone1`
                        , `customerno`
                        , `insertedby`
                        , `insertedon`)
                VALUES (3
                        ,conameParam
                        ,cophoneParam
                        ,customernoParam
                        ,lteamidParam
                        ,todaysdateParam);

                SELECT  LAST_INSERT_ID() 
                INTO    coordinatorParam;

            END IF;

            WHILE counterVar < installCountParam DO

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
                    , `create_timestamp`
                    , status)
                VALUES (aptDateParam
                    ,customernoParam
                    ,lteamidParam
                    ,priorityParam
                    ,0
                    ,locationParam
                    ,timeslotParam
                    ,1
                    ,detailsParam
                    ,coordinatorParam
                    ,todaysdateParam
                    ,0);

                set counterVar=counterVar+1;

            END WHILE;

            SET isexecutedOut = 1;
        
        END;
        COMMIT;
END;
END$$
DELIMITER ;
       
       
--        CALL new_install_request('2017-03-17 10:59:07','2017-03-18','2','Mumbai','3','test','0','','','1','1','50',@is_executed);
--        
--        SELECT @is_executed;