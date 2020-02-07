DELIMITER $$
DROP PROCEDURE IF EXISTS update_lr_details$$
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