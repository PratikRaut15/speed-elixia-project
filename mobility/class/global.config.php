<?php
/*********Admin Module***************/

define("TBL_ADMIN_USER",'user',true);
define("TBL_CLIENT",'client',true);
define("TBL_CLIENT_TYPE",'client_type',true);




define("TBL_CUSTOMER",'customer',true);
// missi 
define("TBL_REMARKS",'remarks',true);
define("TBL_USERF1",'userfield1',true);
define("TBL_USERF2",'userfield2',true);

// services 
define("TBL_SERVICELIST",'servicelist',true);
define("TBL_SERVICECALL",'servicecall',true);
define("TBL_SERVICEMAN",'servicemanage',true);
define("TBL_FLOW",'serviceflow',true);
// service 

// feedback 
define("TBL_FEEDBACKQUESTIONS",'feedbackquestions',true);
define("TBL_FEEDBACKOPTIONS",'feedbackoption',true);
define("TBL_FEEDBACKRESULTS",'feedback_result',true);
// feedback 

// tracking
define("TBL_TRACKEE",'trackee',true);
// tracking 



// devices 
define("TBL_DEVICE",'devices',true);
// devices
define("TBL_DISCOUNT_TYPE",'discount_type',true);
define("TBL_DISCOUNT",'discount',true);


// checkpoints
define("TBL_CKPOINTS",'checkpoint',true);

// alerts
define("TBL_ALERTS",'alerts',true);

// notifications 
define("TBL_NOTIF",'notifications',true);

//timedelayy 
define("WARNING1TIME",'20',true);
define("WARNING2TIME",'15',true);
define("API",'apicalls',true);
define("BRANCH",'branch',true);


// checklist details 

define("TBL_FORM",'checklist_form',true);
define("TBL_FORM_META",'checklist_meta_data',true);
define("TBL_FORM_DATA",'checklist_data_table',true);
define("TBL_FORM_OPTION",'checklist_option_table',true);
define("TBL_FORM_ACTIVE_DATA",'checklist_active_data',true);
define("TBL_FORM_TYPE",'form_type',true);



// visiting charges for customerno 
if($_SESSION['customerno']!=15)
{
define("VISITING_CHARGES",'0',true);
}elseif($_SESSION['customerno']==15){
define("VISITING_CHARGES",'500',true);
}

define("TBL_PAYMENTS",'payment',true);


















?>