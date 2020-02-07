/*
    Name		-	insert_ledger
    Description 	-	Insert Ledger Details
    Parameters		-	ledgername VARCHAR(100),address1 VARCHAR(100),address2 VARCHAR(100),address3 VARCHAR(100),email 				VARCHAR(40),phone VARCHAR(20),pan_no VARCHAR(30),cst_no VARCHAR(30),st_no VARCHAR(30),vat_no 					VARCHAR(30),customernoparam INT,createdby INT,createdon DATETIME
				updatedby INT,updatedon DATETIME
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL insert_ledger				             ('test321','XYZ','PQR','MNPO','test@email.com','2222222222','pAN131','cST31','st3214','vat522',2,6,'2016-04-15 15:00:00',6,'2016-04-1515:21:00',@LASTINSERTID);
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	3 May, 2016
        Reason		-	new out param added to get lastinsertid.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_ledger`$$
CREATE PROCEDURE `insert_ledger`( 
    IN ledgernameParam VARCHAR(100)
    , IN address1Param VARCHAR(100)
    , IN address2Param VARCHAR(100)
    , IN address3Param VARCHAR(100)
    , IN stateParam VARCHAR(100)
    , IN emailParam VARCHAR(40)
    , IN phoneParam VARCHAR(20)
    , IN pan_noParam VARCHAR(30)
    , IN gst_noParam VARCHAR(30)
    , IN cst_noParam VARCHAR(30)
    , IN st_noParam VARCHAR(30)
    , IN vat_noParam VARCHAR(30)
    , IN createdbyParam INT(11)
    , IN createdonParam DATETIME
    , IN updatedbyParam INT(11)
    , IN updatedonParam DATETIME
    , OUT isexecutedOut TINYINT(2)
    , OUT lastinsertidOut  INT(11)
)
BEGIN
    

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
            ROLLBACK;
			/* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error; */
            SET isexecutedOut = 0;
	END;
    BEGIN  

        SET isexecutedOut = 0;

        START TRANSACTION;	 
        BEGIN
            INSERT INTO ledger(ledgername
                , address1 
                , address2 
                , address3
                , state_code
                , email 
                , phone 
                , pan_no 
                , gst_no
                , cst_no 
                , st_no 
                , vat_no 
                , createdby 
                , createdon 
                , updatedby 
                , updatedon) 
            VALUES(ledgernameParam 
                , address1Param 
                , address2Param 
                , address3Param
                , stateParam
                , emailParam 
                , phoneParam 
                , pan_noParam 
                , gst_noParam
                , cst_noParam 
                , st_noParam 
                , vat_noParam 
                , createdbyParam 
                , createdonParam 
                , updatedbyParam 
                , updatedonParam);

            SELECT  LAST_INSERT_ID()
            INTO    lastinsertidOut; 

            SET isexecutedOut = 1;

        END;
        COMMIT; 
    
    END;
    
END$$
DELIMITER ;

