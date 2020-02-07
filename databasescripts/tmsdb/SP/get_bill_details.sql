DELIMITER $$
DROP PROCEDURE IF EXISTS `get_bill_details`$$
CREATE PROCEDURE `get_bill_details`(
		 IN billidparam INT
         ,IN locationidpatam INT
         ,IN custno INT
)
BEGIN
	
	IF(custno = '' OR custno = 0) THEN
	SET custno = NULL;
    END IF;
    IF(billidparam = '' OR billidparam = 0) THEN
	SET billidparam = NULL;
    END IF;	
    IF(locationidpatam = '' OR locationidpatam = 0) THEN
	SET locationidpatam = NULL;
    END IF;

    SELECT 
		billid
        ,billtypeid
        ,invoice_location_id
        ,factory.factoryname as invoice_location
        ,depot.depotname as depotname
        ,depot_location_id
        ,vendor_id
        ,transporter.transportername as vendorname
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
		, billpayable.customerno
		,billpayable.created_on
		,billpayable.updated_on
		,billpayable.created_by
		,billpayable.updated_by
        FROM billpayable
    LEFT JOIN factory on factory.factoryid =  billpayable.invoice_location_id
    LEFT JOIN  depot on depot.depotid = billpayable.depot_location_id
    LEFT JOIN transporter on transporter.transporterid = billpayable.vendor_id
	WHERE (billpayable.customerno = custno OR custno IS NULL)
    AND (billid = billidparam OR billidparam IS NULL)
    AND (invoice_location_id = locationidpatam OR locationidpatam IS NULL)
	AND   billpayable.isdeleted = 0;
        
END$$
DELIMITER ;
