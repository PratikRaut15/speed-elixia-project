-- Insert SQL here.

DELIMITER $$
DROP PROCEDURE IF EXISTS update_orders$$
CREATE PROCEDURE update_orders( 
IN orderid1 INT,
IN clientid INT,
IN stageid INT,
IN expectedordercomplitiondate date,
IN isemailrequested tinyint(1),
IN issmsrequested tinyint(1),
IN isemailsent tinyint(1),
IN issmssent tinyint(1),
IN additionalcost float(6,2),
IN totalamount float(6,2),
IN lostnotes TEXT,
IN  updatedtime DATETIME,
IN updated_by INT
)

BEGIN

UPDATE orders 
	SET 
		clientid = clientid,
		stageid = stageid,
		expectedordercomplitiondate = expectedordercomplitiondate,
		isemailrequested = isemailrequested, 
		issmsrequested = issmsrequested,
		isemailsent = isemailsent,
		issmssent = issmssent,
		additionalcost = additionalcost,
		totalamount = totalamount,
		lostnotes = lostnotes,
	        updatedtime=updatedtime, 
		updated_by = updated_by 
	WHERE 
		orderid = orderid1;

END$$
DELIMITER ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 2, NOW(), 'Ganesh','Order update procedure alter');
