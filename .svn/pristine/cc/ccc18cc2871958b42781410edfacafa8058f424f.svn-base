DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_payment_dump`$$
CREATE  PROCEDURE `insert_payment_dump`( 
	IN vendorcode bigint(11) 
	, IN billno bigint(11)
	, IN clearingdocno varchar(30)
    , IN clearingdate DATE
	, IN refno varchar(30)
    , IN paymentstatus varchar(30)
    , IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	INSERT INTO `payment_dump`(
						`vendor_code`
						, `bill_no`
						, `clearing_doc_no`
                        , `clearing_date`
						, `ref_no`
                        , `payment_status`
                        , `customerno`
						, `created_on`
						, `updated_on`
						, `created_by`
						, `updated_by`) 
				VALUES (vendorcode
					, billno
					, clearingdocno
                    , clearingdate
                    , refno
                    , paymentstatus
                    , customerno
					, todaysdate
					, todaysdate
					, userid
					, userid
					);

END$$
DELIMITER ;
