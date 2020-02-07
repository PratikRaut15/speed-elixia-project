ALTER TABLE lrDetails_draft ADD loading decimal(10,2) Not NUll  AFTER shipment_freight_bill;
ALTER TABLE lrDetails_draft ADD unloading decimal(10,2) Not NUll  AFTER loading;


ALTER TABLE lrDetails ADD loading decimal(10,2) Not NUll  AFTER shipment_freight_bill;
ALTER TABLE lrDetails ADD unloading decimal(10,2) Not NUll  AFTER loading;




DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_lr_details_draft`$$
CREATE PROCEDURE `insert_lr_details_draft`(
		bill_id int
        , IN delivery_no bigint
        , IN lr_no varchar(100)
        , IN shipment_no varchar(30)
        , IN cost_document_no varchar(30)
        , IN truck_type varchar(30)
        , IN route varchar(150)
        , IN vehicle_no varchar(20)
        , IN indentid int
        , IN vehicle_type tinyint
        , IN movement_type tinyint
        , IN cfa_cost decimal(10,2)
        , IN shipment_freight_bill decimal(10,2)
        , IN loading decimal(10,2)
        , IN unloading decimal(10,2)
        , IN loading_charges decimal(10,2)
        , IN unloading_charges decimal(10,2)
        , IN other_charges decimal(10,2)
        , IN multidrop_charges decimal(10,2)
        , IN toll_charges decimal(10,2)
        , IN permit_charges decimal(10,2)
        , IN charges_outword decimal(10,2)
        , IN gprs decimal(10,2)
        , IN noentry_charges decimal(10,2)
        , IN auto_charges decimal(10,2)
        , IN lr_charges decimal(10,2)
        , IN tt_penalty decimal(10,2)
        , IN any_deduction decimal(10,2)
        , In total_delivery_amount decimal(10,2)
        , IN customerno INT
		, IN todaysdate DATETIME
		, IN userid INT
        , OUT current_lrid INT
        
)
BEGIN
    INSERT INTO 
		lrDetails_draft
        (
        bill_id
        ,delivery_no
        ,lr_no
        ,shipment_no
        ,cost_document_no
        ,truck_type
        ,route
        ,vehicle_no
        ,indentid
        ,vehicle_type
        ,movement_type
        ,cfa_cost
        ,shipment_freight_bill
        ,loading
        ,unloading
        ,loading_charges
        ,unloading_charges
        ,other_charges
        ,multidrop_charges
        ,toll_charges
        ,permit_charges
        ,charges_outword
        ,gprs
        ,noentry_charges
        ,auto_charges
        ,lr_charges
        ,tt_penalty
        ,any_deduction
        ,total_delivery_amount
		,customerno
		,created_on
		,updated_on
		,created_by
		,updated_by
        )
        values
        (
        bill_id
        ,delivery_no
        ,lr_no
        ,shipment_no
        ,cost_document_no
        ,truck_type
        ,route
        ,vehicle_no
        ,indentid
        ,vehicle_type
        ,movement_type
        ,cfa_cost
        ,shipment_freight_bill
        ,loading
        ,unloading
        ,loading_charges
        ,unloading_charges
        ,other_charges
        ,multidrop_charges
        ,toll_charges
        ,permit_charges
        ,charges_outword
        ,gprs
        ,noentry_charges
        ,auto_charges
        ,lr_charges
        ,tt_penalty
        ,any_deduction
        ,total_delivery_amount
		,customerno
		,todaysdate
		,todaysdate
		,userid
		,userid
        );
        
        SET current_lrid = LAST_INSERT_ID();
END$$
DELIMITER ;






DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_lr_details`$$
CREATE PROCEDURE `insert_lr_details`(
		bill_id int
        , IN delivery_no bigint
        , IN lr_no varchar(100)
        , IN shipment_no varchar(30)
        , IN cost_document_no varchar(30)
        , IN truck_type varchar(30)
        , IN route varchar(150)
        , IN vehicle_no varchar(20)
        , IN indentid int
        , IN vehicle_type tinyint
        , IN movement_type tinyint
        , IN cfa_cost decimal(10,2)
        , IN shipment_freight_bill decimal(10,2)
        , IN loading decimal(10,2)
        , IN unloading decimal(10,2)
        , IN loading_charges decimal(10,2)
        , IN unloading_charges decimal(10,2)
        , IN other_charges decimal(10,2)
        , IN multidrop_charges decimal(10,2)
        , IN toll_charges decimal(10,2)
        , IN permit_charges decimal(10,2)
        , IN charges_outword decimal(10,2)
        , IN gprs decimal(10,2)
        , IN noentry_charges decimal(10,2)
        , IN auto_charges decimal(10,2)
        , IN lr_charges decimal(10,2)
        , IN tt_penalty decimal(10,2)
        , IN any_deduction decimal(10,2)
        , In total_delivery_amount decimal(10,2)
        , IN customerno INT
		, IN todaysdate DATETIME
		, IN userid INT
        , OUT current_lrid INT
        
)
BEGIN
    INSERT INTO 
		lrDetails
        (
        bill_id
        ,delivery_no
        ,lr_no
        ,shipment_no
        ,cost_document_no
        ,truck_type
        ,route
        ,vehicle_no
        ,indentid
        ,vehicle_type
        ,movement_type
        ,cfa_cost
        ,shipment_freight_bill
        ,loading
        ,unloading
        ,loading_charges
        ,unloading_charges
        ,other_charges
        ,multidrop_charges
        ,toll_charges
        ,permit_charges
        ,charges_outword
        ,gprs
        ,noentry_charges
        ,auto_charges
        ,lr_charges
        ,tt_penalty
        ,any_deduction
        ,total_delivery_amount
		,customerno
		,created_on
		,updated_on
		,created_by
		,updated_by
        )
        values
        (
        bill_id
        ,delivery_no
        ,lr_no
        ,shipment_no
        ,cost_document_no
        ,truck_type
        ,route
        ,vehicle_no
        ,indentid
        ,vehicle_type
        ,movement_type
        ,cfa_cost
        ,shipment_freight_bill
        ,loading
        ,unloading
        ,loading_charges
        ,unloading_charges
        ,other_charges
        ,multidrop_charges
        ,toll_charges
        ,permit_charges
        ,charges_outword
        ,gprs
        ,noentry_charges
        ,auto_charges
        ,lr_charges
        ,tt_penalty
        ,any_deduction
        ,total_delivery_amount
		,customerno
		,todaysdate
		,todaysdate
		,userid
		,userid
        );
        
        SET current_lrid = LAST_INSERT_ID();
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `update_lr_details`$$
CREATE PROCEDURE `update_lr_details`(
		lridparam int
        , IN delivery_noparam bigint
        , IN lr_noparam varchar(100)
        , IN shipment_noparam varchar(30)
        , IN cost_document_noparam varchar(30)
        , IN truck_typeparam varchar(30)
        , IN routeparam varchar(150)
        , IN vehicle_noparam varchar(20)
        , IN indentidparam int
        , IN vehicle_typeparam tinyint
        , IN movement_typeparam tinyint
        , IN cfa_costparam decimal(10,2)
        , IN shipment_freight_billparam decimal(10,2)
        , IN loadingparam decimal(10,2)
        , IN unloadingparam decimal(10,2)
        , IN loading_chargesparam decimal(10,2)
        , IN unloading_chargesparam decimal(10,2)
        , IN other_chargesparam decimal(10,2)
        , IN multidrop_chargesparam decimal(10,2)
        , IN toll_chargesparam decimal(10,2)
        , IN permit_chargesparam decimal(10,2)
        , IN charges_outwordparam decimal(10,2)
        , IN gprsparam decimal(10,2)
        , IN noentry_chargesparam decimal(10,2)
        , IN auto_chargesparam decimal(10,2)
        , IN lr_chargesparam decimal(10,2)
        , IN tt_penaltyparam decimal(10,2)
        , IN any_deductionparam decimal(10,2)
        , In total_delivery_amountparam decimal(10,2)
        , IN custno INT
		, IN todaysdate DATETIME
		, IN userid INT
        
)
BEGIN
    UPDATE lrDetails
    SET 
    delivery_no = delivery_noparam
    , lr_no =lr_noparam
    , shipment_no= shipment_noparam
    , cost_document_no = cost_document_noparam
    , truck_type= truck_typeparam
    , route = routeparam
    , vehicle_no = vehicle_noparam
    , indentid = indentidparam
    , vehicle_type = vehicle_typeparam
    , movement_type = movement_typeparam
    , cfa_cost = cfa_costparam
    , shipment_freight_bill = shipment_freight_billparam
    , loading = loadingparam
    , unloading = unloadingparam
    , loading_charges = loading_chargesparam
    , unloading_charges = unloading_chargesparam
    , other_charges  = other_chargesparam
    , multidrop_charges = multidrop_chargesparam
    , toll_charges = toll_chargesparam
    , permit_charges = permit_chargesparam
    , charges_outword = charges_outwordparam
    , gprs = gprsparam
    , noentry_charges = noentry_chargesparam
    , auto_charges = auto_chargesparam
    , lr_charges = lr_chargesparam
    , tt_penalty = tt_penaltyparam
    , any_deduction = any_deductionparam
    , total_delivery_amount = total_delivery_amountparam
    , updated_on = todaysdate
	, updated_by = userid
    WHERE lrid = lridparam
    AND isdeleted = 0;
END$$
DELIMITER ;





DELIMITER $$
DROP PROCEDURE IF EXISTS `update_lr_details_draft`$$
CREATE PROCEDURE `update_lr_details_draft`(
		lridparam int
        , IN delivery_noparam bigint
        , IN lr_noparam varchar(100)
        , IN shipment_noparam varchar(30)
        , IN cost_document_noparam varchar(30)
        , IN truck_typeparam varchar(30)
        , IN routeparam varchar(150)
        , IN vehicle_noparam varchar(20)
        , IN indentidparam int
        , IN vehicle_typeparam tinyint
        , IN movement_typeparam tinyint
        , IN cfa_costparam decimal(10,2)
        , IN shipment_freight_billparam decimal(10,2)
		, IN loadingparam decimal(10,2)
        , IN unloadingparam decimal(10,2)        
        , IN loading_chargesparam decimal(10,2)
        , IN unloading_chargesparam decimal(10,2)
        , IN other_chargesparam decimal(10,2)
        , IN multidrop_chargesparam decimal(10,2)
        , IN toll_chargesparam decimal(10,2)
        , IN permit_chargesparam decimal(10,2)
        , IN charges_outwordparam decimal(10,2)
        , IN gprsparam decimal(10,2)
        , IN noentry_chargesparam decimal(10,2)
        , IN auto_chargesparam decimal(10,2)
        , IN lr_chargesparam decimal(10,2)
        , IN tt_penaltyparam decimal(10,2)
        , IN any_deductionparam decimal(10,2)
        , In total_delivery_amountparam decimal(10,2)
        , IN custno INT
		, IN todaysdate DATETIME
		, IN userid INT
        
)
BEGIN
    UPDATE lrDetails_draft
    SET 
    delivery_no = delivery_noparam
    , lr_no =lr_noparam
    , shipment_no= shipment_noparam
    , cost_document_no = cost_document_noparam
    , truck_type= truck_typeparam
    , route = routeparam
    , vehicle_no = vehicle_noparam
    , indentid = indentidparam
    , vehicle_type = vehicle_typeparam
    , movement_type = movement_typeparam
    , cfa_cost = cfa_costparam
    , shipment_freight_bill = shipment_freight_billparam
    , loading = loadingparam
    , unloading = unloadingparam
    , loading_charges = loading_chargesparam
    , unloading_charges = unloading_chargesparam
    , other_charges  = other_chargesparam
    , multidrop_charges = multidrop_chargesparam
    , toll_charges = toll_chargesparam
    , permit_charges = permit_chargesparam
    , charges_outword = charges_outwordparam
    , gprs = gprsparam
    , noentry_charges = noentry_chargesparam
    , auto_charges = auto_chargesparam
    , lr_charges = lr_chargesparam
    , tt_penalty = tt_penaltyparam
    , any_deduction = any_deductionparam
    , total_delivery_amount = total_delivery_amountparam
    , updated_on = todaysdate
	, updated_by = userid
    WHERE lrid = lridparam
    AND isdeleted = 0;
END$$
DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `get_lr_details_draft`$$
CREATE PROCEDURE `get_lr_details_draft`(
        IN lridparam INT
        ,IN billid INT
        ,IN custno int
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
	SET custno = NULL;
    END IF;
    IF(billid = '' OR billid = 0) THEN
	SET billid = NULL;
    END IF;
    IF(lridparam = '' OR lridparam = 0) THEN
	SET lridparam = NULL;
    END IF;
    
    SELECT 	
		lrid
		,bill_id
        ,delivery_no
        ,lr_no
        ,shipment_no
        ,cost_document_no
        ,truck_type
        ,route
        ,vehicle_no
        ,vehicle_type
        ,movement_type
        ,cfa_cost
        ,shipment_freight_bill
        ,loading
        ,unloading
        ,loading_charges
        ,unloading_charges
        ,other_charges
        ,multidrop_charges
        ,toll_charges
        ,permit_charges
        ,charges_outword
        ,gprs
        ,noentry_charges
        ,auto_charges
        ,lr_charges
        ,tt_penalty
        ,any_deduction
        ,total_delivery_amount
		,customerno
		,created_on
		,updated_on
		,created_by
		,updated_by
    FROM    lrDetails_draft 
    WHERE (customerno = custno OR custno IS NULL)
    AND (lrid = lridparam OR lridparam IS NULL)
    AND (bill_id = billid OR billid IS NULL)
	AND   isdeleted = 0;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_lr_details`$$
CREATE PROCEDURE `get_lr_details`(
        IN lridparam INT
        ,IN billid INT
        ,IN custno INT
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
	SET custno = NULL;
    END IF;
    IF(billid = '' OR billid = 0) THEN
	SET billid = NULL;
    END IF;
    IF(lridparam = '' OR lridparam = 0) THEN
	SET lridparam = NULL;
    END IF;
    
    SELECT 	
		lrid
		,bill_id
        ,delivery_no
        ,lr_no
        ,shipment_no
        ,cost_document_no
        ,truck_type
        ,route
        ,vehicle_no
        ,vehicle_type
        ,movement_type
        ,cfa_cost
        ,shipment_freight_bill
        ,loading
        ,unloading
        ,loading_charges
        ,unloading_charges
        ,other_charges
        ,multidrop_charges
        ,toll_charges
        ,permit_charges
        ,charges_outword
        ,gprs
        ,noentry_charges
        ,auto_charges
        ,lr_charges
        ,tt_penalty
        ,any_deduction
        ,total_delivery_amount
		,customerno
		,created_on
		,updated_on
		,created_by
		,updated_by
    FROM  lrDetails 
    WHERE (customerno = custno OR custno IS NULL)
    AND (lrid = lridparam OR lridparam IS NULL)
    AND (bill_id = billid OR billid IS NULL)
	AND   isdeleted = 0;
END$$
DELIMITER ;





DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_vendor_payable`$$
CREATE PROCEDURE `insert_vendor_payable`(
        IN billidparam INT
        , IN custno INT
)
BEGIN

DECLARE current_billid int;

DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		ROLLBACK;
	END;
    
    START TRANSACTION;
    
    IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    IF(billidparam = '' OR billidparam = 0) THEN
		SET billidparam = NULL;
    END IF;
    

    INSERT INTO billpayable(
		billtypeid
        ,invoice_location_id
        ,depot_location_id
        ,vendor_id
        ,bill_no
        ,bill_date
        ,description
        ,final_bill_amount
        ,bill_received_date
        ,bill_processed_date
        ,bill_sent_date
        ,grn_no
        ,po_no
        ,bill_remarks
        ,settelement_remarks
        ,due_days 
        , billing_status 
        , due_status 
        , days_for_receiving_bills 
        , process_days 
        , custody 
        , total_custody 
        , payment_done 
        , month_sent 
        , year_sent 
        , payment_bucket 
        , payment_status 
		,customerno
		,created_on
		,updated_on
		,created_by
		,updated_by
        ,isdeleted
    )
     SELECT 	
		billtypeid
        ,invoice_location_id
        ,depot_location_id
        ,vendor_id
        ,bill_no
        ,bill_date
        ,description
        ,final_bill_amount
        ,bill_received_date
        ,bill_processed_date
        ,bill_sent_date
        ,grn_no
        ,po_no
        ,bill_remarks
        ,settelement_remarks
        ,due_days 
        , billing_status 
        , due_status 
        , days_for_receiving_bills 
        , process_days 
        , custody 
        , total_custody 
        , payment_done 
        , month_sent 
        , year_sent 
        , payment_bucket 
        , payment_status 
		,customerno
		,created_on
		,updated_on
		,created_by
		,updated_by
        , isdeleted
	FROM    billpayable_draft bd
    WHERE (bd.customerno = custno OR custno IS NULL)
    AND   (bd.billid = billidparam OR billidparam IS NULL)
    AND   bd.isdeleted = 0;

	SET current_billid = LAST_INSERT_ID();

	IF(current_billid IS NOT NULL OR current_billid > 0) THEN
    
    INSERT INTO lrDetails(
		lrid
		,bill_id
        ,delivery_no
        ,lr_no
        ,shipment_no
        ,cost_document_no
        ,truck_type
        ,route
        ,vehicle_no
        ,indentid
        ,vehicle_type
        ,movement_type
        ,cfa_cost
        ,shipment_freight_bill
        ,loading
        ,unloading
        ,loading_charges
        ,unloading_charges
        ,other_charges
        ,multidrop_charges
        ,toll_charges
        ,permit_charges
        ,charges_outword
        ,gprs
        ,noentry_charges
        ,auto_charges
        ,lr_charges
        ,tt_penalty
        ,any_deduction
        ,total_delivery_amount
		,customerno
		,created_on
		,updated_on
		,created_by
		,updated_by
        ,isdeleted
    )
    SELECT
		lrid
		,current_billid
        ,delivery_no
        ,lr_no
        ,shipment_no
        ,cost_document_no
        ,truck_type
        ,route
        ,vehicle_no
        ,indentid
        ,vehicle_type
        ,movement_type
        ,cfa_cost
        ,shipment_freight_bill
        ,loading
        ,unloading
        ,loading_charges
        ,unloading_charges
        ,other_charges
        ,multidrop_charges
        ,toll_charges
        ,permit_charges
        ,charges_outword
        ,gprs
        ,noentry_charges
        ,auto_charges
        ,lr_charges
        ,tt_penalty
        ,any_deduction
        ,total_delivery_amount
		,customerno
		,created_on
		,updated_on
		,created_by
		,updated_by
        ,isdeleted
	FROM lrDetails_draft ld
	WHERE (ld.customerno = custno OR custno IS NULL)
    AND   (ld.bill_id = billidparam OR billidparam IS NULL)
    AND   ld.isdeleted = 0;
    
    END IF;
        
	

	COMMIT;
    
END$$
DELIMITER ;




-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (22, NOW(), 'Shrikant Suryawanshi','Vendor Payable Changes');

