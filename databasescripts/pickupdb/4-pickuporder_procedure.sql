DELIMITER $$
DROP PROCEDURE IF EXISTS insert_woworders$$
CREATE PROCEDURE insert_woworders( 
IN vendornum VARCHAR (20),
IN transno VARCHAR(35), 
IN awbnum VARCHAR(35),
IN custid varchar(20),
IN customerno INT,
IN  entrytime DATETIME,
OUT lastid INT
)

BEGIN
DECLARE pickupboyid varchar(35);
SET pickupboyid = 0; 

IF EXISTS (SELECT pv.vendorid from pickup_vendor as pv inner join  vendormapping as vm  on pv.vendorid = vm.vendorid WHERE pv.vendorno = vendornum  AND vm.customerid = custid ) THEN
							
				SELECT 	p.pid into pickupboyid 
				FROM	pickup_vendor as pv 
					INNER JOIN vendormapping as vm on pv.vendorid = vm.vendorid  
					LEFT JOIN pinmapping as p on p.pincode = pv.pincode 
				where 	vm.vendor_no = vendornum 
				AND 	vm.customerid = custid 
				AND 	vm.isdeleted=0
				LIMIT 1;

				IF NOT EXISTS(SELECT oid from pickup_order WHERE vendorno = vendornum AND fulfillmentid = transno AND awbno = awbnum ) THEN
					Insert into pickup_order (  customerno ,customerid ,vendorno ,fulfillmentid ,awbno ,pickupboyid) 
					VALUES( customerno ,custid ,vendornum ,transno ,awbnum ,pickupboyid);
					SET lastid = LAST_INSERT_ID();
				END IF;
	  ELSE
				Insert into pickup_orders_notadd ( customerno ,customerid ,vendorno ,fulfillmentid ,awbno ,entry_date) 
				VALUES( customerno ,custid ,vendornum ,transno ,awbnum ,entrytime);
				SET lastid = 0;
			
END IF;
END$$
DELIMITER ;
