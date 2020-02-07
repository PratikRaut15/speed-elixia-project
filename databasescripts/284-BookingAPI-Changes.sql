USE SPEED;

TRUNCATE TABLE booking;

ALTER TABLE booking
DROP COLUMN `drivername`;

ALTER TABLE booking
ADD COLUMN `tripstatus` TINYINT(1) DEFAULT 1 AFTER `vehicleno`;

ALTER TABLE booking
ADD COLUMN `createdby` VARCHAR(50) AFTER `created_on`;

ALTER TABLE booking
ADD COLUMN `updatedby` VARCHAR(50) AFTER `updated_on`;

ALTER TABLE booking
ADD CONSTRAINT UQ_booking_bookingrefno UNIQUE(`bookingrefno`);

ALTER TABLE booking
ALTER COLUMN `isdeleted` SET DEFAULT 0;

DELIMITER $$
DROP PROCEDURE IF EXISTS insert_booking_details$$
CREATE PROCEDURE `insert_booking_details`( 
	IN bookingrefno VARCHAR (25)
	, IN vehicleno VARCHAR (20) 
    , IN tripstatus TINYINT(1)
	, IN expected_tripstarttime DATETIME
	, IN customerno INT
    , IN userid INT
    , OUT currentbookingid INT
	)
BEGIN
	/* Check whether the booking already exists */
	SET currentbookingid = (SELECT	bookingid 
							FROM 	booking 
							WHERE 	bookingrefno = bookingrefno);
	IF (currentbookingid > 0) THEN
			/* 
				If tripstatus is 0 that is cancelled then soft delete the booking 
				If tripstatus is 1 that is booked again with same refno then reactivate the booking 
            */
				UPDATE 	booking
				SET 	tripstatus = tripstatus
						, isdeleted = NOT tripstatus
						, updated_on = NOW()
                        , updatedby = userid
				WHERE 	bookingrefno = bookingrefno;
    ELSE
        INSERT INTO booking (
								bookingrefno
								, vehicleno
								, tripstatus
								, expected_tripstarttime
								, customerno
								, created_on
                                , createdby
								, updated_on
                                , updatedby
							)
		VALUES ( 
					bookingrefno
					, vehicleno
					, tripstatus
					, expected_tripstarttime
					, customerno
					, now()
					, userid
					, now()
                    , userid
				);
				
		SET currentbookingid = LAST_INSERT_ID();
    END IF;
END$$
DELIMITER ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (284, NOW(), 'Mrudang','BookingAPI - Added tripstatus column and Unique constraint for bookingrefno');
