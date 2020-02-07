INSERT INTO `dbpatches` (`patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES (660, '2019-02-05 12:48:00', 'Arvind Thakur','InsertQ SP craeted');

ALTER TABLE eventalerts 
DROP COLUMN temp_sms,
DROP COLUMN temp2_sms,
DROP COLUMN temp3_sms,
DROP COLUMN temp4_sms,
DROP COLUMN temp_email,
DROP COLUMN temp2_email,
DROP COLUMN temp3_email,
DROP COLUMN temp4_email;

ALTER TABLE advancetempalertrange 
ADD COLUMN temp1_intv_sms DATETIME NOT NULL AFTER temp1_max_sms,
ADD COLUMN temp2_intv_sms DATETIME NOT NULL AFTER temp2_max_sms,
ADD COLUMN temp3_intv_sms DATETIME NOT NULL AFTER temp3_max_sms,
ADD COLUMN temp4_intv_sms DATETIME NOT NULL AFTER temp4_max_sms,
ADD COLUMN temp1_intv_email DATETIME NOT NULL AFTER temp1_max_email,
ADD COLUMN temp2_intv_email DATETIME NOT NULL AFTER temp2_max_email,
ADD COLUMN temp3_intv_email DATETIME NOT NULL AFTER temp3_max_email,
ADD COLUMN temp4_intv_email DATETIME NOT NULL AFTER temp4_max_email;

CREATE TABLE advtempeventalerts (
  eaid int(11) PRIMARY KEY AUTO_INCREMENT,
  vehicleid int(11) NOT NULL,
  userid int(11) NOT NULL,
  temp_sms tinyint(1) DEFAULT '0',
  temp2_sms tinyint(1) DEFAULT '0',
  temp3_sms tinyint(1) DEFAULT '0',
  temp4_sms tinyint(1) DEFAULT '0',
  temp_email tinyint(1) DEFAULT '0',
  temp2_email tinyint(1) DEFAULT '0',
  temp3_email tinyint(1) DEFAULT '0',
  temp4_email tinyint(1) DEFAULT '0',
  customerno int(11) NOT NULL
);

INSERT INTO advtempeventalerts(vehicleid
    , userid
    , temp_sms
    , temp2_sms
    , temp3_sms
    , temp4_sms
    , temp_email
    , temp2_email
    , temp3_email
    , temp4_email
    , customerno)
SELECT  vehicleid
    , userid
    , 0
    , 0
    , 0
    , 0
    , 0
    , 0
    , 0
    , 0
    , customerno
FROM    advancetempalertrange
group by userid,vehicleid;



DELIMITER $$
DROP PROCEDURE IF EXISTS InsertQ$$
CREATE PROCEDURE `InsertQ`(
    IN vehicleidParam INT
    , IN devlatParam FLOAT
    , IN devlongParam FLOAT
    , IN typeParam INT
    , IN statusParam TINYINT(1)
    , IN messageParam TEXT
    , IN tempsensorParam TINYINT(4)
    , IN useridParam INT
    , IN customernoParam INT
    , IN todaydateParam DATETIME
)
BEGIN

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */
    END;

    IF (vehicleidParam = 0) THEN
        SET vehicleidParam = NULL;
    END IF;

    IF (customernoParam = 0) THEN
        SET customernoParam = NULL;
    END IF;

    START TRANSACTION;

        IF (vehicleidParam IS NOT NULL AND customernoParam IS NOT NULL) THEN

            INSERT INTO comqueue(customerno
                , vehicleid
                , devlat
                , devlong
                , `type`
                , status
                , message
                , tempsensor
                , timeadded
                , userid) 
            VALUES ( customernoParam
                , vehicleidParam
                , devlatParam
                , devlongParam
                , typeParam
                , statusParam
                , messageParam
                , tempsensorParam
                , todaydateParam
                , useridParam);

        END IF;

    COMMIT;

END$$
DELIMITER ;

UPDATE  dbpatches 
SET     updatedOn = ''
        ,isapplied = 1 
WHERE   patchid = 660;