/*
    Name		-	InsertQ
    Description 	-	To insert entry in comqueue table
    Parameters		-	As listed below
    Module		-	Vehicle Tracking System
    Sub-Modules 	- 	None
    Sample Call		-	call InsertQ('14913','22.442030','73.071350','8','1','GJ 01 DZ 3317 - Temperature is above 8.00°C[19.18]','1','0','720','2019-02-05 12:52:48');
    Created by		-	Arvind Thakur
    Created on          -	05 Feb, 2019
    Change details 	-
    1) 	Updated by	-	
        Updated	on	-	
        Reason		-	
    2) 
*/
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