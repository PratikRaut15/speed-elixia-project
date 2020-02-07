/*
    Name		-	update_ledger
    Description 	-	Update Ledger Details
    Parameters		-	ledgername VARCHAR(100),address1 VARCHAR(100),address2 VARCHAR(100),address3 VARCHAR(100),email 				VARCHAR(40),phone VARCHAR(20),pan_no VARCHAR(30),cst_no VARCHAR(30),st_no VARCHAR(30),vat_no 					VARCHAR(30),customernoparam INT
				updatedby INT,updatedon DATETIME
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL update_ledger('test321','XYZ','PQR','MNPO','test@email.com','2222222222','pAN131','cST31','st3214','vat522',2,6,'2016-04-15 15:21:00');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	16 April, 2016
        Reason		-	New SP.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `update_ledger`$$
CREATE PROCEDURE `update_ledger`( 
      IN ledgeridParam INT
    , IN ledgernameParam VARCHAR(100)
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
    , IN updatedbyParam INT
    , IN updatedonParam DATETIME
    , OUT isexecutedOut TINYINT(2)
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

        SET isexecutedOut = 0;

        START TRANSACTION;	 
        BEGIN

            UPDATE  ledger 
            SET     ledgername = ledgernameParam
                    , address1 = address1Param 
                    , address2 = address2Param
                    , address3 = address3Param
                    , state_code = stateParam
                    , email = emailParam
                    , phone = phoneParam 
                    , pan_no = pan_noParam
                    , gst_no = gst_noParam
                    , cst_no = cst_noParam
                    , st_no = st_noParam
                    , vat_no = vat_noParam
                    , updatedby = updatedbyParam
                    , updatedon = updatedonParam
            WHERE   ledgerid = ledgeridParam 
            AND     isdeleted = 0;

            SET     isexecutedOut = 1;
            
        END;
        COMMIT; 
    
    END;
                
END$$
DELIMITER ;
