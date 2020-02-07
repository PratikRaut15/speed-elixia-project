ALTER TABLE `pickup_order`
ADD `created_on` DATETIME NULL 
, ADD `updated_on` DATETIME NULL 
, ADD `created_by` INT NOT NULL 
, ADD `updated_by` INT NOT NULL ;

ALTER TABLE pickup_order MODIFY COLUMN customerno int(11) AFTER reasonid;
ALTER TABLE pickup_order MODIFY COLUMN isdeleted tinyint(1) NOT NULL DEFAULT 0 AFTER customerno;

ALTER TABLE `pickup_order` CHANGE `pickupdate` `pickupdate` DATE NOT NULL;

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
IN custno INT,
IN entrytime DATETIME,
IN userid INT,
OUT lastid INT
)

BEGIN
	DECLARE pickid VARCHAR(35);

	IF NOT EXISTS (	SELECT 		pv.vendorid 
					FROM 		vendormapping vm
                    INNER JOIN  pickup_vendor pv ON vm.vendorid = pv.vendorid
                    WHERE 		vendor_no = vendornum  
                    AND 		customerid = custid 
					AND 		customerno = custno
                    AND 		pv.isdeleted = 0
                    AND 		vm.isdeleted = 0) THEN
			CALL insert_vendors(pincode,vname,vphone1,vphone2,vaddress,vendornum,custid,custno);		
	  ELSE
			SELECT 		p.pid
            INTO 		pickid
			FROM		pickup_vendor as pv
			INNER JOIN 	vendormapping as vm on pv.vendorid = vm.vendorid
			LEFT JOIN 	pinmapping as p on p.pincode = pv.pincode
			WHERE 		vm.vendor_no = vendornum
			AND 		vm.customerid = custid
			AND 		vm.isdeleted = 0
			LIMIT 1;
			
			IF(pickid IS NULL) THEN
					SET pickid = 0;
			END IF;

			IF NOT EXISTS(SELECT oid FROM pickup_order WHERE vendorno = vendornum AND fulfillmentid = transno AND awbno = awbnum) THEN
				INSERT INTO pickup_order (
											customerid 
											, vendorno 
                                            , fulfillmentid 
                                            , awbno 
                                            , pickupboyid
                                            , pickupdate
                                            , customerno
                                            , created_on
                                            , updated_on
                                            , created_by
                                            , updated_by) 
				VALUES	(
							custid 
							, vendornum 
                            , transno 
                            , awbnum 
                            , pickid
                            , entrytime
                            , custno
                            , entrytime
                            , entrytime
                            , userid
                            , userid
						);
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
IN custid VARCHAR(20),
IN customerno INT
)
BEGIN
	DECLARE vid VARCHAR(35);
	INSERT INTO pickup_vendor ( 
								customerno
                                ,address
                                ,vendorname
                                ,vendorcompany
                                ,phone
                                ,phone2
                                ,pincode
							) 
	VALUES	(
				customerno
				,vaddress
				,vname
				,vname
				,vphone1
				,vphone2
				,pincode
			);
			
	SET vid = LAST_INSERT_ID();
    
	INSERT INTO vendormapping (
								customerid
								, vendorid
                                , vendor_no)
	VALUES	(
					custid
					,vid
					,vendornum
			);		
END$$
DELIMITER ;


 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 7, NOW(), 'Mrudang Vora','Altering order table and modify the SPs');