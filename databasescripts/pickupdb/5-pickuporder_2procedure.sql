
ALTER TABLE `pickup_vendor` DROP `vendorno`;
--------------------------------------------------------
DELIMITER $$
DROP PROCEDURE IF EXISTS insert_woworders$$
CREATE PROCEDURE insert_woworders( 
IN pincode VARCHAR (50),
IN vname VARCHAR (50),
IN vphone1 VARCHAR (50),
IN vphone2 VARCHAR (50),
IN vaddress VARCHAR (200),
IN vendornum VARCHAR (20),
IN transno VARCHAR(35), 
IN awbnum VARCHAR(35),
IN custid varchar(20),
IN customerno INT,
IN  entrytime DATETIME,
OUT lastid INT
)

BEGIN
declare pickid varchar(35);

IF NOT EXISTS (SELECT vendorid from vendormapping  WHERE vendor_no = vendornum  AND customerid = custid AND isdeleted=0) THEN
		call insert_vendors(pincode,vname,vphone1,vphone2,vaddress,vendornum,custid,customerno);		
	  ELSE
			SELECT 	p.pid into pickid
				FROM	pickup_vendor as pv 
					INNER JOIN vendormapping as vm on pv.vendorid = vm.vendorid  
					LEFT JOIN pinmapping as p on p.pincode = pv.pincode 
				where 	vm.vendor_no = vendornum 
				AND 	vm.customerid = custid 
				AND 	vm.isdeleted=0
				LIMIT 1;
				
				IF(pickid IS NULL) THEN
        				SET pickid := 0;
				END IF;


				IF NOT EXISTS(SELECT oid from pickup_order WHERE vendorno = vendornum AND fulfillmentid = transno AND awbno = awbnum ) THEN
					Insert into pickup_order (  customerno ,customerid ,vendorno ,fulfillmentid ,awbno ,pickupboyid) 
					VALUES( customerno ,custid ,vendornum ,transno ,awbnum ,pickid);
					SET lastid = LAST_INSERT_ID();
				END IF;
			
END IF;
END$$
DELIMITER ;


----------------------------------------------------------------

DELIMITER $$
DROP PROCEDURE IF EXISTS insert_vendors$$
CREATE PROCEDURE insert_vendors( 
IN pincode VARCHAR (50),
IN vname VARCHAR (50),
IN vphone1 VARCHAR (50),
IN vphone2 VARCHAR (50),
IN vaddress VARCHAR (200),
IN vendornum VARCHAR (20),
IN custid varchar(20),
IN customerno INT
)
BEGIN
declare vid varchar(35);
		Insert into pickup_vendor ( customerno ,address,vendorname,vendorcompany,phone,phone2,pincode) 
				    VALUES( customerno,vaddress,vname,vname,vphone1,vphone2,pincode);		
	        SET vid = LAST_INSERT_ID();
		Insert into vendormapping (customerid,vendorid,vendor_no)VALUES(custid,vid,vendornum);		
END$$
DELIMITER ;

