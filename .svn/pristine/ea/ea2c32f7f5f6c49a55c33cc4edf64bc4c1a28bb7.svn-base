DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_lr_details`$$
CREATE  PROCEDURE `insert_lr_details`(
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
