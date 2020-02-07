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
