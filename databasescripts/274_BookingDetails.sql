CREATE TABLE `booking` (
  `bookingid` int(11) NOT NULL AUTO_INCREMENT,
  `bookingrefno` varchar(25) NOT NULL,
  `vehicleno` varchar(20) NOT NULL,
  `drivername` varchar(50) NOT NULL,
  `expected_tripstarttime` datetime NOT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `isdeleted` tinyint(1) DEFAULT NULL DEFAULT '0',
  PRIMARY KEY (`bookingid`)
);

	DELIMITER $$
	DROP PROCEDURE IF EXISTS insert_booking_details$$
	/*
		SP Name: insert_booking_details
		Details: Inserts the details from the booking details API
	*/
	CREATE PROCEDURE insert_booking_details( 
	IN bookingrefno VARCHAR (25),
	IN vehicleno VARCHAR (20), 
	IN drivername VARCHAR(50), 
	IN expected_tripstarttime DATETIME,
	IN customerno INT,
    OUT currentbookingid INT
	)
	BEGIN

	INSERT INTO booking (
							bookingrefno
							, vehicleno
							, drivername
							, expected_tripstarttime
							, customerno
							, created_on
							, updated_on
						)
	VALUES ( 
				bookingrefno
				, vehicleno
				, drivername
				, expected_tripstarttime
				, customerno
				, now()
				, now()
			);
            
	SET currentbookingid = LAST_INSERT_ID();

	END$$
	DELIMITER ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (274, NOW(), 'Mrudang Vora','Booking Table');
