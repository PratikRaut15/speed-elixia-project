DELIMITER $$
DROP PROCEDURE IF EXISTS `push_command_server`$$
CREATE PROCEDURE `push_command_server`(
     IN commentParam VARCHAR(100)
    ,IN commandParam VARCHAR(50)
    ,IN uidParam INT(11)
    ,IN customernoParam INT(11)
    ,IN lteamidParam INT(11)
    ,IN todaysdateParam DATETIME
    ,OUT isexecutedOut TINYINT(2)
    )
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

            UPDATE  unit 
            SET      `command`=commandParam
                    ,`setcom`=1
                    , comments = commentParam    
            WHERE   customerno = customernoParam 
            AND     uid = uidParam;

            INSERT INTO  push_command_log (
                    `unitid`
                   ,`customerno` 
                   ,`command`
                   , `comment`
                   , `createdby`
                   , `timestamp`)
            VALUES (uidParam
                    , customernoParam
                    , commandParam
                    , commentParam
                    , lteamidParam
                    , todaysdateParam);

            SET isexecutedOut = 1;
        END;
        COMMIT;
    END;
END$$
DELIMITER ;