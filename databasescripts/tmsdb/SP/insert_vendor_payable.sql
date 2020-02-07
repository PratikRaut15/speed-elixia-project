DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_vendor_payable`$$
CREATE  PROCEDURE `insert_vendor_payable`(
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
        ,isdeleted
    )
    SELECT
		current_billid
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
