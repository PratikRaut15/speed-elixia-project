DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_bank_withdrawal`$$
CREATE PROCEDURE `insert_bank_withdrawal`( 
     IN todaysdateParam DATETIME
    ,IN vendoridParam INT(11)
    ,IN lteamidParam INT(11)
    ,IN chequenoParam VARCHAR(40)
    ,IN bankParam VARCHAR(40)
    ,IN amountParam float
    ,IN typeParam TINYINT(1)
    ,IN statusParam TINYINT(1)
    ,IN responsibleParam INT(11)
    ,IN bankidParam INT(11)
    ,OUT isexecutedOut TINYINT(1)
)
BEGIN

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
          /*  GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error;  */
            SET isexecutedOut = 0;
        END;

        BEGIN
            DECLARE brsidVar INT(11);

            SET isexecutedOut = 0;

            START TRANSACTION;
            BEGIN

                INSERT INTO `bank_reconc_stmt`(`timestamp`
                        , `vendorid`
                        , `userid`
                        , `chequeno`
                        , `bank`
                        , `amount`
                        , `type`
                        , `status`
                        , `responsible_id`
                        , `is_deleted`
                        , `bank_id`) 
                VALUES (todaysdateParam
                        ,vendoridParam
                        ,lteamidParam
                        ,chequenoParam
                        ,bankParam
                        ,amountParam
                        ,typeParam
                        ,statusParam
                        ,responsibleParam
                        ,0
                        ,bankidParam);

                SELECT  LAST_INSERT_ID()
                INTO    brsidVar;

                INSERT INTO `bank_transaction_history` (
                        `brs_id`
                        ,`timestamp`
                        ,`vendorid`
                        ,`userid`
                        ,`chequeno`
                        ,`bank`
                        ,`amount`
                        ,`type`
                        ,`status`
                        ,`responsible_id`
                        ,`bank_id`)
                VALUES(brsidVar
                        ,todaysdateParam
                        ,vendoridParam
                        ,lteamidParam
                        ,chequenoParam
                        ,bankParam
                        ,amountParam
                        ,typeParam
                        ,statusParam
                        ,responsibleParam
                        ,bankidParam);

                SET isexecutedOut = 1;
            END;
            COMMIT;
        END;

END$$
DELIMITER ;
