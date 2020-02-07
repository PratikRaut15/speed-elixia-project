SET @patchId = 730;
SET @patchDate = '2019-11-05 19:20:00';
SET @patchOwner = 'Arvind Thakur';
SET @patchDescription = 'AllanaSon Transporter Inactive Vehicle List';


INSERT INTO dbpatches(patchid, patchdate, appliedby, patchdesc, isapplied)
VALUES (@patchId, @patchDate, @patchOwner, @patchDescription, '0');

DELIMITER $$
DROP PROCEDURE IF EXISTS `getCustomerList`$$
CREATE PROCEDURE `getCustomerList`(
    IN customernoParam TEXT
)
BEGIN

    IF (customernoParam != '') THEN
    
        SELECT  c.customerno
                , c.customername
                , c.customercompany
                , c.customerTypeId
        FROM    customer AS c
        WHERE   c.customercompany <> 'Elixia Tech' 
        AND     FIND_IN_SET(c.customerno,customernoParam)
        ORDER BY c.customerno ASC; 

    END IF;

END$$
DELIMITER ;

UPDATE dbpatches SET isapplied = 1, updatedOn =  DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE) WHERE patchid = @patchId;
