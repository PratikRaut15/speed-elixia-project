INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('525', '2017-07-26 11:19:00','Arvind Thakur','Bank Reconciliation Statement Task', '0');

CREATE TABLE IF NOT EXISTS `bank_reconc_stmt` (
    `id` INT(11) PRIMARY KEY auto_increment,
    `timestamp` DATETIME,
    `customerno` INT(11),
    `vendorid` INT(11),
    `userid` INT(11),
    `chequeno` VARCHAR(40),
    `bank` VARCHAR(40),
    `amount` float,
    `type` TINYINT(1) COMMENT '0 - cash , 1-cheque',
    `status` TINYINT(1) COMMENT '1 - Received , 2 - Deposited,3-Generated,4-Dispatched,5-Cleared',
    `responsible_id` INT(11),
    `is_deleted` TINYINT(1),
    `bank_id` INT(11)
);

CREATE TABLE IF NOT EXISTS `bank` (
    `id` INT(11) PRIMARY KEY auto_increment,
    `name` VARCHAR(40));

INSERT INTO `bank`(`id`,`name`)
VALUES (1,'IDBI');
INSERT INTO `bank`(`id`,`name`)
VALUES (2,'HDFC');


CREATE TABLE IF NOT EXISTS `bank_transaction_history` (
    `transid` INT(11) PRIMARY KEY auto_increment,
    `brs_id` INT(11),
    `timestamp` DATETIME,
    `customerno` INT(11),
    `vendorid` INT(11),
    `userid` INT(11),
    `chequeno` VARCHAR(40),
    `bank` VARCHAR(40),
    `amount` float,
    `type` TINYINT(1) COMMENT '0 - cash , 1-cheque',
    `status` TINYINT(1) COMMENT '1 - Received , 2 - Deposited,3-Generated,4-Dispatched,5-Cleared',
    `responsible_id` INT(11),
    `bank_id` INT(11)
);

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_bank_deposit`$$
CREATE PROCEDURE `insert_bank_deposit`( 
     IN todaysdateParam DATETIME
    ,IN customernoParam INT(11)
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
                        , `customerno`
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
                        ,customernoParam
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
                        ,`customerno`
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
                        ,customernoParam
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


UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 525;