DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_bill_details_draft`$$
CREATE PROCEDURE `insert_bill_details_draft`(
         IN billtypeid INT
        , IN invoice_location_id INT
        , IN depot_id INT
        , IN vendor_id INT
        , IN bill_no VARCHAR(100)
        , IN bill_date date
        , IN description text
        , IN final_bill_amount int
        , IN bill_received_date date
        , IN bill_processed_date date
        , IN bill_sent_date date
        , IN grn_no int
        , IN po_no int
        , IN remarks_regarding_bill text
        , IN remarks_regarding_settlement text
        , IN due_days int 
        , IN billing_status varchar(30) 
        , IN due_status varchar(30) 
        , IN days_for_receiving_bills tinyint
        , IN process_days tinyint
        , IN custody tinyint
        , IN total_custody tinyint 
        , IN payment_done tinyint
        , IN month_sent varchar(10) 
        , IN year_sent int
        , IN payment_bucket varchar(50) 
        , IN payment_status varchar(50)
        , IN customerno INT
		, IN todaysdate DATETIME
		, IN userid INT
        , OUT current_billid INT
        
)
BEGIN
    INSERT INTO 
		billpayable_draft
        (
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
        )
        values
        (
        billtypeid
        ,invoice_location_id
        ,depot_id
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
        ,remarks_regarding_bill
        ,remarks_regarding_settlement
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
		,todaysdate
		,todaysdate
		,userid
		,userid
        );
        
        SET current_billid = LAST_INSERT_ID();
END$$
DELIMITER ;
