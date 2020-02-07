/*
	**** NOTE: IT INVOLVES DELETE STATEMENTS AND CAN NOT BE REVERTED ****
    Name			-	delete_unit_details
    Description 	-	Delete unit data in all the required tables.
						SPECIFICALLY CREATED TO DELETE DUPLICATE UNITS
    Parameters		-	unitnoParam
						, custnoParam - Customer number in which this unit no is already present and needs to be deleted
    Module			-	Team
    Sub-Modules 	- 	
    Sample Call		-	CALL delete_unit_details('904414', 177, @isUnitDeleted);
						SELECT @isUnitDeleted;
    Created by		-	Mrudang Vora
    Created on		- 	20 July, 2016
    Change details 	-	
    1) 	Updated by	- 	
		Updated	on	- 	
		Reason		-	
	2) 
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_unit_details`$$
CREATE PROCEDURE `delete_unit_details` (
	IN unitnoParam VARCHAR(11)
	, IN custnoParam INT
    , OUT isUnitDeleted  INT
)
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		/* 
			It would work for mysql version >= 5.6.4 and mariadb.
            Uncomment the following lines to check error if you have above mentioned versions.        
        */
		/*
		GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE, 
		@errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
		SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
		SELECT @full_error;
        */
		ROLLBACK;
        SET isUnitDeleted = 0;
	END;
	START TRANSACTION;
		/* UNIT TABLE OPERATIONS */
		SET @uidToBeDeleted = (SELECT uid FROM unit WHERE unitno = unitnoParam AND customerno = custnoParam LIMIT 1);
		DELETE FROM unit 			WHERE uid = @uidToBeDeleted;

		/* VEHICLE TABLE OPERATIONS */
        SET @vehicleidToBeDeleted = (SELECT vehicleid FROM vehicle WHERE uid = @uidToBeDeleted LIMIT 1);
        DELETE FROM vehicle 		WHERE vehicleid = @vehicleidToBeDeleted;

		/* DRIVER TABLE OPERATIONS */        
        DELETE FROM driver 			WHERE vehicleid = @vehicleidToBeDeleted;

		/* IGNITION ALERT TABLE OPERATIONS */
		DELETE FROM ignitionalert 	WHERE vehicleid = @vehicleidToBeDeleted;
       
		/* EVENT ALERT TABLE OPERATIONS */
		DELETE FROM eventalerts 	WHERE vehicleid = @vehicleidToBeDeleted;

		/* DEVICES TABLE OPERATIONS */
        SET @simcardidToBeDeleted = (SELECT simcardid FROM devices WHERE uid = @uidToBeDeleted LIMIT 1);
        
        DELETE FROM devices 		WHERE uid = @uidToBeDeleted;
		
		/* SIMCARD TABLE OPERATIONS */
		DELETE FROM simcard 		WHERE id = @simcardidToBeDeleted;

		/* DAILY REPORT OPERATIONS */
		DELETE FROM dailyreport		WHERE vehicleid = @vehicleidToBeDeleted;
		
        SET isUnitDeleted = 1;
    COMMIT;

END$$
DELIMITER ;