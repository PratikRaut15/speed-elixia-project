/**
Change details 	-
    1)	Updated by	-   Sanjeet
		Updated	on	- 	04 January, 2019
        Reason		-	Get Duplicate Units and CustomerNo by Vehicle No
		call		-	CALL get_duplicate_units_by_vehicle_no('MP07HB1809');

*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_duplicate_units_by_vehicle_no`$$
CREATE PROCEDURE `get_duplicate_units_by_vehicle_no`(
    IN vehicleNoParam VARCHAR(40)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        
        /*GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */
        
    END;
    
        IF (vehicleNoParam = '') THEN
            SET vehicleNoParam = NULL;
        END IF;
        IF (vehicleNoParam IS NOT NULL) THEN
        
            SELECT      v.vehicleid as vehicleId ,v.vehicleno as vehicleNo, v.uid, v.customerno as customerNo
            FROM        vehicle v
			INNER JOIN	devices d on d.uid = v.uid
			INNER JOIN	unit u on u.uid = v.uid
			INNER JOIN	driver dr on dr.vehicleid = v.vehicleid
			WHERE		v.vehicleno = vehicleNoParam
            AND         v.isDeleted = 0
            ;

        END IF;
END$$
DELIMITER ;
-- CALL get_duplicate_units_by_vehicle_no('MH46F4431');
CALL get_duplicate_units_by_vehicle_no('MH46F4431');