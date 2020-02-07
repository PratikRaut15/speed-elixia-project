DELIMITER $$
DROP PROCEDURE IF EXISTS update_bill_details_draft$$
CREATE PROCEDURE `update_bill_details_draft`(
		 IN billidparam INT
        , IN billtypeidparam INT
        , IN invoice_location_idparam INT
        , IN depot_idparam INT
        , IN vendor_idparam INT
        , IN bill_noparam VARCHAR(100)
        , IN bill_dateparam date
        , IN descriptionparam text
        , IN final_bill_amountparam int
        , IN bill_received_dateparam date
        , IN bill_processed_dateparam date
        , IN bill_sent_dateparam date
        , IN grn_noparam int
        , IN po_noparam int
        , IN remarks_regarding_billparam text
        , IN remarks_regarding_settlementparam text
        , IN due_daysparam int 
        , IN billing_statusparam varchar(30) 
        , IN due_statusparam varchar(30) 
        , IN days_for_receiving_billsparam tinyint
        , IN process_daysparam tinyint
        , IN custodyparam tinyint
        , IN total_custodyparam tinyint 
        , IN payment_doneparam tinyint
        , IN month_sentparam varchar(10) 
        , IN year_sentparam int
        , IN payment_bucketparam varchar(50) 
        , IN payment_statusparam varchar(50)
        , IN custno INT
		, IN todaysdate DATETIME
		, IN userid INT
        
)
BEGIN
    update billpayable_draft SET
			billtypeid = billtypeidparam
            , invoice_location_id = invoice_location_idparam
            , depot_location_id = depot_idparam
            , vendor_id = vendor_idparam
            , bill_no = bill_noparam 
            , bill_date = bill_dateparam
            , description = descriptionparam
            , final_bill_amount = final_bill_amountparam
            , bill_received_date = bill_received_dateparam
            , bill_processed_date = bill_processed_dateparam
            , bill_sent_date = bill_sent_dateparam
            , grn_no = grn_noparam
            , po_no = po_noparam
            , bill_remarks = remarks_regarding_billparam
            , settelement_remarks = remarks_regarding_settlementparam
            , due_days = due_daysparam
			, billing_status = billing_statusparam
			, due_status = due_statusparam
			, days_for_receiving_bills = days_for_receiving_billsparam
			, process_days = process_daysparam
			, custody = custodyparam
			, total_custody = total_custodyparam
			, payment_done = payment_doneparam
			, month_sent = month_sentparam
			, year_sent = year_sentparam
			, payment_bucket = payment_bucketparam
			, payment_status = payment_statusparam
            , updated_on = todaysdate
			, updated_by = userid
	WHERE billid =  billidparam;
    
END$$
DELIMITER ;
