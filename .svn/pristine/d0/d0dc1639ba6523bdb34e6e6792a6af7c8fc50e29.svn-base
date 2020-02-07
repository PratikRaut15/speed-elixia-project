INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('13', '2019-01-25 19:30:00', 'Yash Kanakia','Distributor Changes', '0');

DROP TABLE IF EXISTS `distributor_customer_details`;
CREATE TABLE `distributor_customer_details`
    (dcId INT PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(100),
    customercompany VARCHAR(100),
    address VARCHAR(200),
    email VARCHAR(100),
    phone VARCHAR(20),
    address_proof_fileName text,
    address_proofPath text,
    photo_proof_fileName text,
    photo_proof_Path text,
    createdBy INT,
    createdOn datetime,
    updatedBy INT,
    updatedOn datetime,
    is_deleted tinyInt);

DELIMITER $$
DROP procedure IF EXISTS `insert_distributorCustomer_details`$$
CREATE PROCEDURE `insert_distributorCustomer_details`(
    IN customerNameParam VARCHAR(100),
    IN companyNameParam VARCHAR(100),
    IN addressParam  VARCHAR(200),
    IN phoneParam  VARCHAR(20),
    IN emailParam  VARCHAR(100),
    IN teamIdParam INT,
    IN todayParam  datetime,
    OUT isExecutedOutParam INT,
    OUT distributorIdOut INT

)
BEGIN

 BEGIN

        /*ROLLBACK;
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;*/

    END;

            SET isExecutedOutParam = 0;

            BEGIN

                INSERT INTO distributor_customer_details(
                    customer_name,
                    customercompany,
                    address,
                    email,
                    phone,
                    createdBy,
                    createdOn,
                    is_deleted
                )VALUES(
                   customerNameParam,
                   companyNameParam,
                   addressParam,
                   emailParam,
                   phoneParam,
                   teamIdParam,
                   todayParam,
                   0
                );



            SET isExecutedOutParam = 1;
            SET distributorIdOut = LAST_INSERT_ID();

            END;



    COMMIT;

END$$

DELIMITER ;



DELIMITER $$
DROP procedure IF EXISTS `update_distributor_fileName`$$
CREATE PROCEDURE `update_distributor_fileName`(
    IN distributorIdParam INT,
    IN file_pathParam text,
    IN file_nameParam  text,
    IN proof_type  tinyInt,
    IN teamIdParam INT,
    IN todayParam  datetime,
    OUT isExecutedOutParam INT

)
BEGIN

 BEGIN

        /*ROLLBACK;
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;*/

    END;

            SET isExecutedOutParam = 0;

            BEGIN
            
            IF proof_type = 1 THEN
                UPDATE distributor_customer_details
                SET address_proof_fileName=file_nameParam,
                    address_proofPath=file_pathParam,
                    updatedBy=teamIdParam,
                    updatedOn=todayParam
                WHERE dcId= distributorIdParam;
            
            SET isExecutedOutParam = 1;
            END IF;
            
            IF proof_type = 2 THEN
                UPDATE distributor_customer_details
                SET photo_proof_fileName=file_nameParam,
                    photo_proof_Path=file_pathParam,
                    updatedBy=teamIdParam,
                    updatedOn=todayParam
                WHERE dcId= distributorIdParam;
            
            SET isExecutedOutParam = 1;
            END IF;

            
            END;

    COMMIT;

END$$

DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `get_distributor_details`$$
CREATE PROCEDURE `get_distributor_details`(
IN teamIdParam INT,
IN dcIdParam INT)
BEGIN

 BEGIN

       /* ROLLBACK;
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;*/

    END;
    
    IF(teamIdParam =0 OR teamIdParam IS NULL)THEN
        SET teamIdParam = NULL;
    END IF;
    
    IF(dcIdParam =0 OR dcIdParam IS NULL)THEN
        SET dcIdParam = NULL;
    END IF;
    
SELECT dc.*,t.name as distributor_name
FROM
    distributor_customer_details dc
    INNER JOIN team t on t.teamid =  dc.createdBy
WHERE
   ( createdBy = teamIdParam
        OR teamIdParam IS NULL)
    AND (dcId =  dcIdParam
        OR dcIdParam IS NULL);


END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `update_distributorCustomer_details`$$
CREATE PROCEDURE `update_distributorCustomer_details`(
    IN dcIdParam INT,
    IN customerNameParam VARCHAR(100),
    IN companyNameParam VARCHAR(100),
    IN addressParam  VARCHAR(200),
    IN phoneParam  VARCHAR(20),
    IN emailParam  VARCHAR(100),
    IN teamIdParam INT,
    IN todayParam  datetime,
    OUT isExecutedOutParam INT,
    OUT distributorIdOut INT

)
BEGIN

 BEGIN

       /* ROLLBACK;
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;*/

    END;

            SET isExecutedOutParam = 0;

            BEGIN

                UPDATE distributor_customer_details
                SET customer_name=customerNameParam,
                    customercompany=companyNameParam,
                    address=addressParam,
                    email=emailParam,
                    phone=phoneParam,
                    updatedBy=teamIdParam,
                    updatedOn=todayParam
                WHERE dcId = dcIdParam;


            SET isExecutedOutParam = 1;
            SET distributorIdOut = dcIdParam;

            END;


END$$

DELIMITER ;



DROP TABLE IF EXISTS `distributor_vehicle_details`;
CREATE TABLE `distributor_vehicle_details`(
dvId INT PRIMARY KEY AUTO_INCREMENT,
dcId INT,
vehicleNo VARCHAR(20),
engineNo VARCHAR(20),
chasisNo VARCHAR(20),
is_deleted tinyInt,
createdBy INT,
createdOn datetime,
updatedBy INT,
updatedOn datetime);



DELIMITER $$
DROP procedure IF EXISTS `insert_distributor_vehicle_details`$$
CREATE PROCEDURE `insert_distributor_vehicle_details`(
    IN dcIdParam INT,
    IN vehNoParam VARCHAR(100),
    IN engNoParam  VARCHAR(200),
    IN chaNoParam  VARCHAR(20),
    IN teamIdParam INT,
    IN todayParam  datetime,
    OUT isExecutedOutParam INT
)
BEGIN

 BEGIN

        /*ROLLBACK;
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;*/

    END;

            SET isExecutedOutParam = 0;

            BEGIN

                INSERT INTO distributor_vehicle_details(
                    dcId,
                    vehicleNo,
                    engineNo,
                    chasisNo,
                    createdBy,
                    createdOn,
                    is_deleted
                )VALUES(
                   dcIdParam,
                   vehNoParam,
                   engNoParam,
                   chaNoParam,
                   teamIdParam,
                   todayParam,
                   0
                );



            SET isExecutedOutParam = 1;

            END;



    COMMIT;

END$$

DELIMITER ;




DELIMITER $$
DROP procedure IF EXISTS `get_distributor_vehicle_details`$$
CREATE PROCEDURE `get_distributor_vehicle_details`(
IN teamIdParam INT,
IN dcIdParam INT,
IN dvIdParam INT)
BEGIN

 BEGIN

       /* ROLLBACK;
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;*/

    END;
    
    IF(teamIdParam =0 OR teamIdParam IS NULL)THEN
        SET teamIdParam = NULL;
    END IF;
    
    IF(dcIdParam =0 OR dcIdParam IS NULL)THEN
        SET dcIdParam = NULL;
    END IF;
    
    IF(dvIdParam =0 OR dvIdParam IS NULL)THEN
        SET dvIdParam = NULL;
    END IF;
    
SELECT dv.*,t.name as distributor_name
FROM
    distributor_vehicle_details dv
    INNER JOIN team t on t.teamid =  dv.createdBy
WHERE
    (createdBy = teamIdParam
        OR teamIdParam IS NULL)
    AND (dcId =  dcIdParam
        OR dcIdParam IS NULL)
    AND (dvId =  dvIdParam
        OR dvIdParam IS NULL);
        


END$$

DELIMITER ;



DELIMITER $$
DROP procedure IF EXISTS `update_distributor_vehicle_details`$$
CREATE PROCEDURE `update_distributor_vehicle_details`(
    IN dvIdParam INT,
    IN vehNoParam VARCHAR(100),
    IN engNoParam VARCHAR(100),
    IN chaNoParam  VARCHAR(200),
    IN teamIdParam INT,
    IN todayParam  datetime,
    OUT isExecutedOutParam INT
)
BEGIN

 BEGIN

       /* ROLLBACK;
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;*/

    END;

            SET isExecutedOutParam = 0;

            BEGIN

               UPDATE distributor_vehicle_details
                SET
                    vehicleNo=vehNoParam,
                    engineNo=engNoParam,
                    chasisNo=chaNoParam,
                    updatedBy=teamIdParam,
                    updatedOn=todayParam

                WHERE   dvId=dvIdParam;
                
            SET isExecutedOutParam = 1;
            END;


END$$

DELIMITER ;

UPDATE  dbpatches
SET     patchdate = '2019-01-25 19:30:00'
        ,isapplied =1
WHERE   patchid = 13;