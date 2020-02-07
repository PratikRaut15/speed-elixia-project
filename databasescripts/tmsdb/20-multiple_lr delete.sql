Create index index_customerno on lrDetails_draft (customerno);

Create index index_customerno on lrDetails (customerno);


DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_lr_details_draft`$$
CREATE PROCEDURE `delete_lr_details_draft`(
		 IN lridparam varchar(50)
        , IN custno INT
		, IN todaysdate DATETIME
		, IN userid INT
        
)
BEGIN
    update lrDetails_draft SET
			isdeleted = 1
	WHERE FIND_IN_SET(lrid,lridparam)
    AND customerno = custno;
    
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_lr_details`$$
CREATE PROCEDURE `delete_lr_details`(
		 IN lridparam varchar(50)
        , IN custno INT
		, IN todaysdate DATETIME
		, IN userid INT
        
)
BEGIN
    update lrDetails SET
			isdeleted = 1
	WHERE FIND_IN_SET(lrid,lridparam)
    AND customerno = custno;
    
END$$
DELIMITER ;









-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (20, NOW(), 'Shrikant Suryawanshi','Delete Multiple LR ');

