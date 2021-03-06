<?php

/**
 * This class would be used to define constants and static variables used in Elixiaspeed.
 *
 */
class speedConstants {
    /* SMS  LENGTH */
    const CURL_TIMEOUT_SECS = 90;
    const PER_SMS_CHARACTERS = 160;
    const MOBITRACK_LICENSE_LENGTH = 16;
    const IST_TIMEZONE = "Asia/Kolkata";
    const CAIRO_TIMEZONE = "Africa/Cairo";
    const DUBAI_TIMEZONE = "Asia/Dubai";
    const ROLE_ELIXIR = "elixir";
    const ALLOWED_IPS = "202.94.163.27,103.87.165.147,103.87.165.6,127.0.0.1,::1";
    //const ALLOWED_IPS = "202.177.242.88,127.0.0.1,::1";
    //
    //<editor-fold defaultstate="collapsed" desc="Customer No Constants">
    const PROBITY_CUSTNO = "15,18,21,277,283,289,293,302,48";
    const ROUTE_DASHBOARD_CUSTNO = "69,73,81,95,181,458,399,132,563,682,71";
    const ROUTE_WISE_TRACKING_CUSTNO = "132,563";
    const CUSTNO_NXTDIGITAL = 391;
    const CUSTNO_SAFEANDSECURE = 206;
    const CUSTNO_RKFOODLANDS = 328;
    const CUSTNO_MONGINISCAIRO = 97;
    const CUSTNO_PERKINELMER = 421;
    const CUSTNO_MDLZ = 116;
    const CUSTNO_APTINFRA = 563;
    const CUSTNO_COLDEX = 613;
    const CUSTNO_STELLAR = 675;
    const CUSTNO_GATI = 644;
    const CUSTNO_LAXMIPARIWAHAN = 646;
    const CUSTNO_CUREFIT = 636;
    const CUSTNO_DELEX = 132;
    const CUSTNO_ODOMETER_RESET = '328';
    const CUSTNO_MS_LOGIDTICS = 833;
    const CUSTNO_TRANSWORLD = 742;
    const CUSTNO_APMT = 745;
    const CUSTNO_PHARM_EASY = 991;

    /* Cron Cnostants For Customer No 52 */
    const API_EXPEDITORS_URL_TEST = 'https://api-dot-iot-expeditors-qa.appspot-preview.com/gsecloc/api/locations/shipment';
    const API_EXPEDITORS_URL = 'https://api-dot-iot-expeditors.appspot-preview.com/gsecloc/api/locations/shipment';

    const REQUEST_TIMEOUT = 180;
    const API_EXPEDITORS_TIMEOUT = 60;
    const API_EXPEDITORS_TOKEN = '26C8365567CACA889EA3598C938DD8E76CC51CFA';
    const API_EXPEDITORS_GCI = 'G0837875';
    const MAHINDRA_CUSTOMERNO = 64;
    const CUSTNO_MRKOOL = 503;
    const CUSTNO_NESTLE = 473;
    const CUSTNO_GHGC = 524;
    const CUSTNO_GTRACK = 613;
    const CUSTNO_SAHARA_ROADLINES = 353;
    const CUSTNO_REFCON = 643;

    const CUSTNO_DELHI_BARODA_ROAD = 1003;
    const CUSTNO_JAY_KAY_FOOD_CARRIERS = 1004;
    const CUSTNO_NAWAB_CARRIERS = 1006;
    const CUSTNO_RAJHANS = 1007;
    const CUSTNO_RAJIV_TRANSPORT_CO = 1009;
    const CUSTNO_COOL_HUB = 1010;
    const CUSTNO_SHAH_LOGISTICS = 1011;
    const CUSTNO_TANWAR_FRESHNESS_CARRIER = 1012;
    const CUSTNO_S_K_FROZEN_FOOD_CARRIER = 1013;
    const CUSTNO_COOLWAYS_CARRIER_PVT_LTD = 1014;
    const CUSTNO_SYNERGY_AUTOMATION_INDIA = 1016;
    const CUSTNO_ANIL_TRANSPORT_OR_KOMAL_KOOL = 1018;
    const CUSTNO_CCSI_LOGISTICS = 1022;
    const CUSTNO_JUNAID_TRANSPORT = 1008;
    const CUSTNO_HASMAT_ALI = 1019;
    const CUSTNO_ALLANA = 984;
    const CUSTNO_TANWAR_LOGISTICS_SOL_PVT_LTD = 1017;
    const CUSTNO_A_COOL = 1033;
    const CUSTNO_ARYAVEER = 1034;
    const CUSTNO_INDO_GULF_CARRIER = 1035;
    const CUSTNO_KHATRI_ROADLINES = 1036;
    const CUSTNO_M_S_FROZEN = 1037;
    const CUSTNO_NAVJOT_FROZEN = 1038;
    const CUSTNO_SAGAR_ROADLINES = 1040;
    const CUSTNO_SATINDER_KUMAR = 1042;
    const CUSTNO_SHREE_KRISHNA_CONTAINER_MOVERS = 1002;
    const CUSTNO_SHUSHMA_EVERFRESH = 1005;
    const CUSTNO_RELICORP_LOGISTICS = 1049;
    const CUSTNO_COLDRUSH = 1015;
    const CUSTNO_FROST_GLOBAL = 1075;

    const CUSTNO_SARFARAZ_ALI = 1082;
    const CUSTNO_SARTAJ_ALI = 1083;
    const CUSTNO_ASIF_ALI = 1084;
    const CUSTNO_ABID_ALI = 1085;

    const TEMP_CONFLICT_BUZZER_CUSTOMER = '575,473,421';
    const API_EXPEDITORS_GCI_SUPREME = 'G2071784';
    const CUSTNO_RAJESH_ROADLINES = 761;
    const CUSTNO_MITCON = 701;
    const ALLOWED_CUST_FOR_ANNEXURE = array('64', '206', '211');

    const VEH_REGNO_EXPRESSION = '/((^([A-Za-z]{2})([0-9]{1,2})((?:[A-Za-z])?(?:[A-Za-z]{0,2})?)([0-9]{4})$)|(^([0-9]{4})([A-Za-z]{2})([0-9]{1,2})((?:[A-Za-z])?(?:[A-Za-z]{0,2})?))$)/';

    const UPDATE_BID_DETAILS_BY_SMS = 'http://uat-erp.elixiatech.com/vendorapi/updateBroadcastedBidBySMS';

    //</editor-fold>
    //
    //<editor-fold defaultstate="collapsed" desc="Modules Constants">
    const MODULE_VTS = 1; // Vehicle Tracking Solution
    const MODULE_FMS = 2; // Fleet Managment Solution OR Maintenance Module
    const MODULE_DRIVERMAPPING = 3; //
    const MODULE_LOCATIONSHARING = 4;
    const MODULE_CHKPTEXCEPTION = 5;
    const MODULE_TEAM = 6;
    //</editor-fold>
    //
    //<editor-fold defaultstate="collapsed" desc="Date Time Constants">
    const DEFAULT_DATETIME = "d-m-Y H:i";
    const DEFAULT_DATE = "d-m-Y";
    const DATE_Ymd = "Y-m-d";
    const DEFAULT_TIME = "d-m-Y H:i";
    const TIME_hia = "h:i a";
    const TIME_Hi = "H:i";
    const TIME_His = "H:i:s";
    const MONTH_DAY_YEAR_hia = speedConstants::DEFAULT_DATETIME;
    const DEFAULT_TIMESTAMP = "Y-m-d H:i:s";
    //</editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Mail Constants">
    /* Admin Emails */
    const adminemail = "sanketsheth@elixiatech.com";
    const accounts_email = "accounts@elixiatech.com";
    const software_email = "software@elixiatech.com";
    const sales_email = "sales@elixiatech.com";
    const support_email = "support@elixiatech.com";
    //const bccemail = "mrudang.vora@elixiatech.com,mrudangvora@gmail.com, sshrikanth@elixiatech.com, sahilg@elixiatech.com, shrisurya24@gmail.com";
    const bccemail = "";
    const FROM_SENDER = 'From: Elixia Speed <noreply@elixiatech.com> \r\n';
    const FROM_NAME = "Elixia Speed";
    const FROM_EMAIL = "noreply@elixiatech.com";
    const WORDWRAP_COUNT_EMAIL_BODY = 300;
    /* Email Text */
    const Thanks = "<br/><br/>Thanks <br/>";
    const CompanyName = "Elixia Tech Solutions Ltd.<br/>";
    const Portallink = '<a href = "http://speed.elixiatech.com" title = "speed.elixiatech.com" target = "_blank">speed.elixiatech.com</a>.<br/>';
    const CompanyImage = '<br/><br/><img src = "http://speed.elixiatech.com/images/elixia_logo_75.png" alt = "Elixia Tech Solutions Pvt. Ltd." /><br/>';
    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Image Constants">
    /* Icons */
    const editimage = "<i class='icon-pencil'></i>";
    const deleteimage = "<img src='../../images/delete1.png'/>";
    const viewimage = "<img src='../../images/history.png'/>";
    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Messages Constants">
    const API_MANDATORY_PARAM_MISSING = "Mandatory parameters missing or not in required format. Please resend the request with required parameters.";
    const API_INVALID_USERKEY = "Invalid User Details.";
    const API_SUCCESS = "Response generated successfully.";
    const API_DATA_NOT_FOUND = "Data not available";
    const API_ERROR_MESSAGE = "OOPs something has gone wrong. Please contact Elixia Support Team.";
    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="DB Constants">
    const SP_GET_ODOMETER_READING = "get_odometer_reading";
    const SP_GET_VEHICLEWAREHOUSE_DETAILS = "get_vehiclewarehouse_details";
    const SP_GET_VEHICLEWAREHOUSE_DETAILS_VTS = "get_vehiclewarehouse_details_vts ";
    const SP_GET_VEHICLEWAREHOUSE_ECODE_DETAILS = "get_vehiclewarehouse_ecode_details"; // For Client Code
    const SP_GET_VEHICLES_DRIVERS_USERS = "get_vehicles_drivers_users";
    const SP_MAP_VEHICLE_DRIVER_USER = "map_vehicle_user_driver";
    const SP_CHECK_VEHICLE_USER_MAPPING = "check_vehicle_user_mapping";
    const SP_AUTHENTICATE_FOR_LOGIN = "authenticate_for_login";
    const SP_AUTHENTICATE_FOR_TEAM_LOGIN = "authenticate_for_team_login";
    const SP_SPEED_FORGOT_PASSWORD = "speed_forgot_password";
    const SP_UPDATE_NEWFORGOTPASSWORD = "update_newforgotpassword";
    const SP_INSERT_SMSLOG = "insert_smslog";
    const SP_INSERT_LOGIN_HISTORY = "insert_login_history";
    const SP_GET_BUS_STOPS = "get_bus_stops";
    const SP_GET_BUS_ROUTES = "get_bus_routes";
    const SP_GET_ALL_CUSTOMER = "get_all_customer";
    const SP_GET_CONTACT_PERSON_OWNER = "get_contact_person_owner";
    const SP_GET_CONTACT_PERSON_ACCOUNT = "get_contact_person_account";
    const SP_GET_CONTACT_PERSON_COORDINATOR = "get_contact_person_coordinator";
    const SP_GET_CUSTOMER_NOT_CRM = "get_customer_not_allot_crm";
    const SP_GET_LEDGER_MAP_CUST = "get_ledger_map_cust";
    const SP_GET_ALL_VEHICLEID_FOR_CUST = "get_all_vehicleid_for_customer";
    const SP_GET_LEDGER_FOR_VEHICLEID = "get_ledger_for_vehicle_id";
    const SP_GET_PENDING_INVOICES = "get_pending_invoices";
    const SP_GET_PENDING_RENEWAL = "get_pending_renewal";
    const SP_GET_EXPIRED_DEVICES = "get_expired_devices";
    const SP_GET_WILL_EXPIRE_DEVICES = "get_will_expire_devices";
    const SP_GET_LOW_SMS_LEFT_CUST = "get_low_sms_left_cust";
    const SP_GET_SMS_CONSUME_FRM_SMSLOG = "get_sms_consume_frm_smslog";
    const SP_GET_SMS_CONSUME_FRM_COMQ = "get_sms_consume_frm_comq";
    const SP_GET_SMS_CONSUME_FRM_SMSLOG_DETAILS = "get_sms_consume_frm_smslog_details";
    const SP_GET_SMS_CONSUME_FRM_COMQ_DETAILS = "get_sms_consume_frm_comq_details";
    const SP_INSERT_CHECKPOINT_EXCEPTION = "insertCheckpointException";
    const SP_GET_SMS_DETAILS_CONSUME_YESTERDAY = "get_sms_detail_consume_yesterday";
    const SP_GET_EMAIL_IDS = "get_all_admin_email_id";
    const SP_GET_CHKPNT_DETAIL = "get_chkpoint_details";
    const SP_REGISTER_DEVICE = "register_device";
    const SP_REPLACE_DEVICE = "replace_device";
    const SP_REPLACE_SIM = "replace_sim";
    const SP_REPLACE_BOTH = "replace_both";
    const SP_REMOVE_BOTH = "remove_unit_sim";
    const SP_REINSTALLDEV = "re_install_device";
    const SP_REPAIR = "repair";
    const SP_PULL_TEAM = "pull_team";
    const SP_ELIXIR_UNIT = "unit_of_teamid";
    const SP_ELIXIR_SIM = "sim_of_teamid";
    const SP_CUST_UNIT_SIM_VEH = "unit_sim_veh_of_cust";
    const SP_CUST_SIM = "sim_of_cust";
    const SP_CUST_VEH = "veh_of_cust";
    const SP_INSERTREALTIMEDATA = "insertRealtimeData";
    const SP_GET_REALTIMEDATA = "get_RealtimeData";
    const SP_GET_SMS_STATUS = "getSmsStatus";
    const SP_UPDATE_VEHICLE_SMSLOCK = "update_vehicle_smslock";
    const SP_UPDATE_USER_SMSLOCK = "update_user_smslock";
    const SP_RESET_SMS_COUNT = "resetSMSCount";
    const SP_GET_PROFIT_LOSS_ANALYSIS = "get_profit_loss_analysis";
    const VEH_SMS_LOCK = 15;
    const USER_SMS_LOCK = 15;
    const SP_INSERT_CATEGORY = "insert_category";
    const SP_UPDATE_CATEGORY = "update_category";
    const SP_DELETE_CATEGORY = "delete_category";
    const SP_INSERT_BANK_STATEMENT = "insert_bank_statement";
    const SP_UPDATE_BANK_STATEMENT = "update_bank_statement";
    const SP_DELETE_BANK_STATEMENT = "delete_bank_statement";
    const SP_ADD_BANK_STATEMENT_TO_TALLY = "add_bank_statement_to_tally";
    const SP_GET_CATEGORY = "get_category";
    const SP_GET_BANK_STATEMENT = "get_bank_statement";
    const SP_GET_BANK_STATEMENT_SUMMARY = "get_bank_statement_summary";
    const SP_GET_CUSTOMFIELD_CUSTOMER = "get_customField_customer";
    const SP_PUSH_COMMAND_SERVER = "push_command_server";
    const SP_SUSPECT_UNIT = "suspect_unit";
    const SP_NEW_INSTALL_REQUEST = "new_install_request";
    const SP_PULL_BUCKET_LIST = "pullBucketList";
    const SP_PULL_COORDINATOR = "pullCoordinator";
    const SP_PULL_REASON = "pullReason";
    const SP_EDIT_BUCKET_OPERATION = "editBucketOperation";
    const SP_EDIT_BUCKET_CRM = "editBucketCRM";
    const SP_PULL_MY_TICKET = "pullMyTicket";
    const SP_PULL_TICKET_STATUS = "pullTicketStatus";
    const SP_PULL_TICKET_PRIORITY = "pullTicketPriority";
    const SP_PULL_TICKET_TYPE = "pullTicketType";
    const SP_ADD_TICKET = "addTicket";
    const SP_EDIT_TICKET = "editTicket";
    const SP_ADD_NOTE = "addNote";
    const SP_PULL_NOTE = "pullNote";
    const SP_PULL_EMAIL = "pullemail";
    const SP_PURCHASE_UNIT = "purchase_unit";
    const SP_UPDATE_LEDGER = "update_ledger";
    const SP_INSERT_LEDGER = "insert_ledger";
    const SP_INSERT_TAX_INVOICE = "insert_tax_invoice";
    const SP_INSERT_BANK_DEPOSIT = "insert_bank_deposit";
    const SP_INSERT_BANK_WITHDRAWAL = "insert_bank_withdrawal";
    const SP_GEOTRACKER_UPDATE_DEVICE_DETAILS = "geotracker_update_device_details";
    const SP_ELIXIASMS_INSERT_REQUEST = "elixiasms_insert_request";
    const SP_API_UPDATE_DEVICE_DETAILS = "api_update_device_details";
    const SP_INSERT_TELALERT = "insertTelAlertLog";
    const SP_UPDATE_TELALERT = "updateTelAlertLog";
    const SP_GET_TELALERT = "getTelAlertLog";
    const SP_GPSPROVIDER_UPDATE_DEVICE_DETAILS = "gpsprovider_update_device_details";
    //<freshchat> SP by Yash Kanakia
    const SP_INSERT_CHATDETAILS = "insert_chatdetails";
    const SP_FETCH_CHATDETAILS = "fetch_chatdetails";
    const SP_GET_PHONENO_ROUTE_CHKPT = "get_phoneno_route_chkpt";
    //Support sp added by - Kartik Joshi 31-01-2018
    const SP_GET_PRODUCTS = "get_products";
    const SP_INSERT_TICKET = "insert_ticket";
    const SP_GET_TICKETS = "get_tickets";
    const SP_PULL_NOTES = "pull_notes";
    const SP_GET_TICKETS_TEAM = "get_tickets_team";
    const PRODUCT_ID = 1;
    const SP_INSERT_NOTE = "insert_note";
    const SP_UPDATE_TICKET_TEAM = "update_ticket_team";
    const SP_GET_EMAILS = "get_emails";
    const SP_INSERT_MAIL_ID = "insert_email";
    const SP_FETCH_VIEW_TICKETS = "fetchTickets";
    // </editor-fold>
    //Dockets - Kartik Joshi 23-02-2018
    const SP_GET_INTERACTION_TYPES = "get_interaction_types";
    const SP_GET_PURPOSE_TYPES = "get_purpose_types";
    const SP_GET_CRM = "get_crm";
    const SP_FETCH_DOCKETS = "fetch_dockets";
    const SP_INSERT_DOCKET = "insert_docket";
    const SP_GET_CUSTOMERS = "fetch_customers";
    const SP_GET_PRIORITIES = "fetch_priorities";
    const SP_GET_TEAM_LIST = "fetch_team_list";
    const SP_GET_TICKET_TYPES = "get_ticket_types";
    const SP_FETCH_TICKETS = "fetch_tickets";
    const SP_GET_STATUS = "fetch_status";
    const SP_EDIT_DOCKET = "edit_docket";
    const SP_FETCH_BUCKETS = "get_buckets";
    const SP_FETCH_OVERDUE_TICKETS = "fetchOverdueTickets";
    const SP_UPDATE_TICKET = "updateTicket";
    //Dockets -Yash Kanakia (Merged 08/03/2017)
    const SP_ADD_CORDINATOR_DETAILS = "insert_cordinator";
    const SP_ADD_BUCKET_LIST = "insert_bucket";
    const SP_VIEW_CORDINATOR = 'get_cordinator';
    const SP_VIEW_TIMESLOT = 'get_timeslot';
    const SP_PULL_BUCKET_HISTORY = 'pullBucketHistory';
    //<editor-fold defaultstate="collapsed" desc="Error Constants-Taken From Table-ErrorMsgMaster">
    const ERRNO_PHONE_NOT_AVAILABLE = 1;
    const ERRNO_INSUFFICIENT_SMS = 2;
    const ERRNO_EMAIL_NOT_AVAILABLE = 3;
    const ERRNO_GCM_NOT_AVAILABLE = 4;
    const ERRNO_SMS_API_ISSUE = 5;
    //</editor-fold>
    //
    //<editor-fold defaultstate="collapsed" desc="Temperature Constants">
    const TEMP_DEGREE = "<sup>o</sup>C";
    const TEMP_WARNING = "<img alt='Error' src='../../images/warning.png' title='Unable To Fetch Temperature' />";
    const TEMP_WARNING_TEXT = "Wirecut";
    const TEMP_MUTE = "<img alt='Mute' src='../../images/mute.png' title='Mute Temperature' width='15px' height='15px' />";
    const TEMP_UNMUTE = "<img alt='Unmute' src='../../images/unmute.png' title='Unmute Temperature' width='15px' height='15px' />";
    const TEMP_NOTACTIVE = "Not Active";
    const TEMP_WIRECUT = "Wire Cut";
    //</editor-fold>
    //
    //<editor-fold defaultstate="collapsed" desc="Cron Reports">
    const REPORT_SUMMARY = 1;
    const REPORT_TRAVEL_HISTORY = 2;
    const REPORT_VEHICLE_MOVEMENT = 3;
    const REPORT_STOPPAGE_ANALYSIS = 4;
    const REPORT_TEMPERATURE = 5;
    const REPORT_TEMPERATURE_COMPLIANCE = 6;
    const REPORT_TRANSACTIONAL = 7;
    const REPORT_DIGITAL_HISTORY = 8;
    const REPORT_LOGIN_HISTORY = 9;
    const REPORT_CHECKPOINT_OWNER_LOG = 10;
    const REPORT_MONTHLY_ROUTE_SUMMARY_REPORT = 11;
    const REPORT_REALTIME_DATA = 12;
    const REPORT_SMS_CONSUMED_DETAILS = 13;
    const REPORT_TEMPERATUR_MIN_MAX_SUMMARY = 14;
    const REPORT_SECONDARY_SUMMARY = 15;
    const REPORT_DAILY_COMPLIANCE_REPORT = 16;
    const REPORT_WEEKLY_COMPLIANCE_REPORT = 17;
    const REPORT_TRIP_SUMMARY = 18;
    const REPORT_INACTIVE_VEHICLE = 19;
    const REPORT_NIGHT_TRAVELLING = 20;
    const REPORT_STOPPAGE_ALERT = 21;
    const REPORT_GROUPWISE_TEMPERATURE = 22;
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Cron Alerts">
    const ALERT_TEMPERATURE = 8;
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Report Constants">
    const REPORT_HTML = "HTML";
    const REPORT_PDF = "PDF";
    const REPORT_XLS = "XLS";
    const REPORT_EMAIL = "EMAIL";
    //</editor-fold>
    //

    //<editor-fold defaultstate="collapsed" desc="Door">
    const IS_AC_ON = "1,3,5,7";
    const IS_DOOR_1 = "0,1,4,5";
    const IS_DOOR_2 = "0,1,2,3";
    const CUST_528_DOOR_BIG = "Door Big";
    const CUST_528_DOOR_SMALL = "Door Small";
    //</editor-fold>
    //suport ka SPs
    // const SP_GET_EMAIL_ID_SUPPORT = "get_all_user_email_id";
    // const SP_CHECK_EMAIL_ID_SUPPORT = "get_email_id";
    // const SP_INSERT_EMAIL_ID_SUPPORT = "insert_email_id";
    //team SPs by Yash Kanakia
    const SP_ADD_CUSTOMER = "insert_customer";
    const SP_EDIT_CUSTOMER = "edit_customer";
    const SP_EDIT_SMS = "edit_sms"; // Not used as of now.
    const SP_ADD_CUSTOMER_DETAILS = "insert_customer_details";
    const SP_EDIT_CUSTOMER_DETAILS = "edit_customer_details";
    const SP_REMOVE_CUSTOMER = "delete_customer"; //Not used because customer is never deleted
    const SP_ADD_SECONDARY_USER = "insert_secondary_user";
    const SP_EDIT_SECONDARY_USER = "edit_secondary_user";
    const SP_ADD_USER = "insert_user"; //while creating customer
    const SP_EDIT_BUCKET_LIST = "edit_bucket";
    const SP_VIEW_USER = "get_user_list";
    const SP_VIEW_CUSTOMER = "get_customer_list";
    const SP_VIEW_BUCKETLIST = "get_bucket_list";
    const SP_VIEW_CUSTOMER_ADDITIONAL_DETAILS = "get_customer_additional_details";
    const SP_VIEW_PENDING_AMOUNT = "get_pending_amount";
    const SP_VIEW_CREDIT_AMOUNT = "get_credit_amount";
    const SP_VIEW_SIM_COUNT = "get_sim_count";
    const SP_TOTAL_BUCKET = 'get_total_bucket';
    const SP_VIEW_TIMEZONE = 'get_timezone';
    const SP_VIEW_INDUSTRY = 'get_industry';
    const SP_VIEW_SALES_PERSON = 'get_sales_person';
    const SP_VIEW_CUSTOMER_DETAILS = 'get_customer_details';
    const SP_VIEW_CONTACT_TYPEMASTER = 'get_contact_typemaster';
    const SP_VIEW_DEVICE_DETAILS = 'get_device_list';
    const SP_VIEW_SIM_DETAILS = 'get_sim_details';
    const SP_VIEW_LOGIN_HISTORY = 'get_login_history';
    const SP_VIEW_NOTES = 'get_notes';
    const SP_ADD_CUSTOMER_NOTE = "add_customer_notes";
    const SP_VIEW_BUCKET_DETAILS = 'get_bucket_details';
    const SP_ADD_E_BUCKET_LIST = "insert_e_bucket";
    const SP_GET_VEHICLE_NUMBER = "get_vehicle_list";
    const SP_DELETE_FROM_TICKETUSERMAPPING = 'delete_from_ticketusermapping';
    const SP_INSERT_INTO_TICKETUSERMAPPING = 'insert_into_ticketusermapping';
    const SP_FETCH_TICKETS_FOR_DOCKET = "fetchTicketsForDocket";
    const SP_FETCH_BUCKETS_FOR_DOCKET = "fetchBucketsForDocket";
    const SP_FETCH_VIEW_BUCKETS = "fetch_buckets";
    const SP_INSERT_EMAIL_ID_SUPPORT = "insert_email_id";
    const SP_GET_TICKET_COUNT = "get_ticket_count"; // To count tickets for CRM Dashboard
    const SP_GET_BUCKET_COUNT = "get_bucket_count"; // To count buckets for CRM Dashboard
    const SP_GET_CUSTOMER_COUNT = "get_customer_count";
    const SP_INSERT_ADDITIONAL_AMOUNT = "insert_additional_charges"; //To add enhancement or
    // operational extra charges
    const SP_GET_TICKET_ANALYSIS = "getTicketAnalysis"; // To show analysis of tickets for CRM
    const SP_GET_STATUS_BUCKET = "fetch_status_bucket"; // To fetch bucket status
    const SP_GET_BUCKET_ANALYSIS = "getBucketAnalysis"; // To show analysis of buckets for CRM
    const SP_GET_UNMAPPED_VEHICLE_ANALYSIS = "unmappedVehicle"; //To show no. of u_mapped vehicle
    const SP_GET_LEDGER_DETAILS = "getLedgerDetails"; //Invoice Details related to Ledger
    const SP_GET_LEDGER_PAYMENT_DETAILS = "getLedgerPaymentDetails"; //Payment Details related to
    //Ledger
    const SP_GET_ALL_LEDGERS = "get_all_ledgers";
    const SP_GET_ALL_HARDWARE_INVOICES = "get_hardware_invoices"; //ESG Invoices SP
    const SP_GET_LEDGER = "fetch_ledger";
    const SP_GET_PENDING_INVOICE = "fetch_pending_invoices";
    const SP_GET_PAID_INVOICE = "fetch_paid_invoices";
    const SP_GET_INVOICE_PAYMENT = "get_invoice_payment"; //GetInvoicePaymentMapping4Grid
    const SP_GET_INVOICE_SUB_PAYMENT = "get_payment_sub_details"; //Get SubDetails of Payment
    const SP_GET_PAYMENT_MODE = "get_payment_mode"; //Get Payment Mode
    const SP_INSERT_INVOICE_PAYMENT = "insert_invoice_payments"; //Insert Payments against Invoices
    const SP_GET_INVOICE_PAYMENT_MAPPING = "get_payment_mapping"; //Get Individual Payment Mapping
    const SP_EDIT_INVOICE_PAYMENT = "update_invoice_payment"; //Update Individual Payment
    const SP_GET_INVOICE_PAYMENT_OLD = "get_invoice_payment_old"; //GetOldInvoicePaymentMapping4Grid
    const SP_GET_DEVICE_LOCATION = "get_device_location"; //For Unit Analysis
    const SP_GET_DEVICE_LOCATION_COUNT = "get_device_loctn_count"; //For Unit Analysis
    const SP_GET_OPENING_BALANCE = "get_opening_balance"; //Opening Balance for ledger
    const GET_UNIT_WITH_CUSTOMER = "fetch_unit_customer"; //Check unit exist with cust.
    const SP_INSERT_DUPLICATE_UNITS = "insert_duplicate_unit"; //Insert Duplicate units
    const SP_INSERT_DUPLICATE_UNIT_LOG = "insert_duplicate_unit_log"; //Duplicate units Logs
    const SP_GET_DEVICES_INFO = "get_customer_device_info"; //Devices info for customer verification
    const SP_GET_ALL_UNIT_CUSTOMER = "get_all_units_customer"; //fetch all units of customer
    const SP_GET_UNIT_DETAILS = "get_unit_details"; //fetch unit details for troubleshooting
    const SP_GET_FIXED_BUDGET = "fixed_budgeting_amount"; //Amount for budeting (invoice amount)
    const SP_GET_CUSTOMERS_BUDGETING = "fetch_customers_budgeting"; //ledgerwise customers
    const SP_GET_MAX_INVOICE_ID = "fetch_max_invoiceid_budgeting"; //fetch max invoiceid
    const SP_GET_PRODUCT_COUNT = "fetch_sales_summary_report"; //Fetch orders for sales summary report
    const SP_PURCHASE_SIM = "purchase_sim"; //To purchase Simcards

    const SP_GET_TASK_TEAM_LIST = "fetch_task_teamList"; //fetchTeamList for Tasks to add Roles
    const SP_GET_TEAM_ROLES = "fetch_team_task_roles"; //Fetch Roles of Team Members
    const SP_INSERT_TASK_TEAM_MEMEBER = "insert_task_team_memeber"; //Insert TaskTember mapping with
    //role
    const SP_INSERT_TASK = "insert_task"; //Insert task into tasks table
    const SP_GET_DEVELOPERS = "fetch_developers"; //Fetch Developers of Software Team
    const SP_GET_TESTERS = "fetch_testers"; //Fetch Testers of Software Team
    const SP_GET_TEAM_TASKS = "fetch_tasks"; //Fetch Tasks
    const SP_UPDATE_TASK_TEAM = "update_task_team"; //Update Tasks
    const SP_GET_TASK_STATUS = "fetch_task_status"; //fetch task status
    const SP_GET_TASK_TEAM_MEMBERS = "fetch_task_members"; //
    const SP_SEARCH_TASK = "searchTask";
    const SP_INSERT_INTO_TIMESHEET = "insert_timesheet"; //Insert into timesheet table
    const SP_GET_TIMESHEET_DETAILS = "fetch_timesheet_details";
    const SP_GET_TIMESHEETS = "fetch_timesheet"; //GET timesheets
    const SP_GET_MIGRATORS = "fetch_migrators"; //Fetch Migrators of Software Team
    const SP_DELETE_TASK = "delete_tasks"; //delete Tasks
    const SP_INSERT_TASK_REMARKS = "insert_task_remarks"; //Insert Task Remarks
    const SP_GET_REMARKS_HISTORY = "fetch_task_remarks"; //fetch remarks against task

    const SP_INSERT_INTO_NEWS_LETTER_SUBS = "insert_into_newsLetter_Subscription"; // INSERT N.L. SUBSCRIBER
    const SP_UPDATE_NEWS_LETTER_SUBS = "update_newsLetter_Subscription"; // UPDATE N.L. SUBSCRIBER
    const SP_INSERT_NEWS_LETTER_CONTENT = "insert_newsLetterContent"; // INSERT NEWSLETTER CONTENT
    const SP_GET_NEWS_LETTER_CONTENT = "fetch_newsLetterContent"; // FETCH NEWSLETTER CONTENT
    const SP_UPDATE_NEWS_LETTER_CONTENT = "update_newsLetterContent"; // UPDATE NEWSLETTER
    const SP_FETCH_EMAIL_CONTENT_NAME = "fetch_newsLetterContent_name"; // FETCH NAMES OF NEWSLETTERS
    const SP_FETCH_NEWS_LETTER_SUBS = "fetch_newsLetter_Subscription_Emails"; // FETCH EMAIL OF ELIXIA SUBSCRIBER
    const SP_FETCH_GUID = "fetch_guId"; // FETCH GUID FOR ELIXIA SUBSCRIBER
    const SP_FETCH_INVOICE_LINKS_DETAILS = "fetch_invoice_link_details"; // FETCH INVOICE LINKS TO DWLD
    const SP_INSERT_CONTACT_PERSON_DETAILS = "insert_contactperson_details"; // INSERT CUSTOMER CONTACTS
    const SP_CHECK_UNIQUE_USERKEY = "fetch_userkey"; // CHECK DUPLICATE USERKEY IN SPEED
    const SP_FETCH_UNIT_BACKUP_DATE = "fetch_unitBackup_date"; // FETCH UNIT BACKUP DATE
    const SP_ADD_USER_TRACE = "insert_user_trace"; // INSERT USER IN TRACE
    const SP_FETCH_CONTACT_PIPELINE = "fetch_contact_pipeline"; // FETCH SALES CONTACT
    const SP_UPDATE_CUSTOMER_PIPLELINE = "update_customer_pipleine"; //UPDATE CUSTOMER IN PIPELINE
    const SP_CHECK_UNIQUE_USERKEY_TRACE = "fetch_userkey_trace"; // CHECK DUPLICATE USERKEY IN TRACE
    const SP_FETCH_PRODUCT_URL = "fetch_product_url"; //FETCH URLS TO INSERT CUSTOMER
    const SP_GET_LOGIN_TREND = "get_login_trend"; //FETCH LOGIN TREND OF CUSTOMER
    const SP_COMPANY_ROLE = "get_company_role"; // GET COMPANY ROLES
    const SP_ADD_TEAM_MEMBER = "insert_team_member"; //insert new team member
    const SP_GET_DEPARTMENT = "get_department";
    const SP_ADD_DISTRIBUTOR_CUSTOMER_DETAIL = "insert_distributorCustomer_details"; //Cust details by distri.
    const SP_INSERT_DISTRIBUTOR_FILENAME = "update_distributor_fileName"; //same SP to insert/update files.
    const SP_GET_DISTRIBUTOR_DETAILS = "get_distributor_details";
    const SP_EDIT_DISTRIBUTOR_CUSTOMER_DETAIL = "update_distributorCustomer_details";
    const SP_INSERT_DISTRIBUTOR_VEHICLE = "insert_distributor_vehicle_details";
    const SP_FETCH_DISTRIBUTOR_VEHICLES = "get_distributor_vehicle_details";
    const SP_EDIT_DISTRIBUTOR_VEHICLE_DETAIL = "update_distributor_vehicle_details";
    const SP_EDIT_TEAM_MEMBER = "update_team_member";
    const SP_EDIT_TEAM_ACC_SETTING = "update_team_account_settings"; //username/password.
    const SP_ADD_ITEM_MASTER = "insert_item_master"; //Invoice generation items.
    const SP_GET_ITEM_MASTER = "fetch_item_master";
    const SP_EDIT_ITEM_MASTER = "update_item_master"; //Invoice generation items.
    const SP_ADD_COLUMN_ITEM_MASTER = "insert_column_item_masterDetails";
    const SP_RENAME_COLUMN_ITEM_MASTER = "update_column_item_masterDetails";
    const SP_FETCH_ITEM_MASTER_DETAILS = "fetch_item_master_details";
    const SP_EDIT_ITEM_MASTER_DETAILS = "update_item_master_details";
    //SalesPipeline SPs by Kartik  Joshi 08/05/2018
    const SP_VERSION_FILE = "versionFile";
    const SP_FETCH_PIPELINE_FILES = "fetchPipelineFiles";
    const SP_REVIVE_PIPELINE = "revive_pipeline";
    const SP_AUTOFREEZE_PIPELINE = "autoFreeze_pipeline";
    const SP_FETCH_SR_STATS = "fetchSRStats";
    //departments for team members
    const TEAM_DEPARTMENT_SOFTWARE = 1;
    const TEAM_DEPARTMENT_OPERATIONS = 2;
    const TEAM_DEPARTMENT_ACCOUNTS = 3;
    const TEAM_DEPARTMENT_SALES = 4;
    const TEAM_DEPARTMENT_CRM = 5;
    const TEAM_DEPARTMENT_FE = 6;
    const TEAM_DEPARTMENT_MANAGEMENT = 7;
    const TEAM_DEPARTMENT_OTHERS = 8;
    //roles for team members
    const TEAM_ROLE_HEAD = 1;
    const TEAM_ROLE_ADMIN = 2;
    const TEAM_ROLE_SERVICE = 3;
    const TEAM_ROLE_OTHERS = 4;
    //scheduling invoices by kartik joshi
    const SP_SCHEDULE_INVOICE = "scheduleInvoice";
    const SP_FETCH_INVOICE_CYCLES = "fetch_invoice_cycles";
    const SP_FETCH_INVOICE_REMINDERS = "fetchInvoiceReminders";
    const SP_RESCHEDULE_INVOICE = "rescheduleInvoice";
    const SP_APPROVE_INVOICE = "approveInvoice";
    const SP_FETCH_INVOICE_PRODUCTS = "fetch_invoice_products";
    const SP_GET_INVOICE_REMINDERS = "getInvoiceReminders";
    const SP_EDIT_INVOICE_REMINDER = "editInvoiceReminder";
    const SP_DELETE_INVOICE_REMINDER = "deleteInvoiceReminder";

    /* Add Consignor And Consignee For Trip Module */
    const SP_INSERT_TRIP_CONSIGNOR = "insert_trip_consignor";
    const SP_INSERT_TRIP_CONSIGNEE = "insert_trip_consignee";
    const SP_INSERT_TRIP_LR_MAPPING = "insert_trip_lr_mapping";
    const SP_GET_TRIP_DROPPOINTS = "get_trip_droppoints";

    const SP_MERGE_ORDER_DETAILS = "merge_order_details";
    const SP_MERGE_DELIVERY_CHALLAN = "merge_delivery_challan";
    const SP_GET_DASHBOARD_TRIPDETAILS = "get_dashboard_tripdetails";
    const SP_GET_DASHBOARD_VEHICLES = "get_dashboard_vehicles";
    const SP_GET_DASHBOARD_VEHICLEDETAILS_FOR_ETA = "get_dashboard_vehicledetails_for_eta";
    const SP_UPDATE_TRIP_LOG = "update_trip_log";
    const SP_GET_TRIPDETAILS_DATA = "get_tripdetails_data";
    const SP_GET_TRIP_LR_DETAILS = "get_trip_lr_details";
    const SP_GET_VEHICLE_DATA_FROM_UNIT_NO = "get_vehicle_data_from_unit_no";
    const SP_GET_VEHICLE_NUMBER_BY_VEHICLEID = "get_vehicle_number_by_vehicleid";

    const SP_INSERT_FREEZE_LOG = "insert_freeze_log";

    /* Mean Kinetic Temperature */
    const KELVIN_VALUE = "273.15"; // convert Degree to Kelvin by adding the Kelvin value ex ; 19.02 + 273.15
    const DELTA_H = "83.14472"; //kJ/mole
    const GAS_CONSTANT = "8.314472"; // J/mole/degree // "0.008314472"; // kJ/mole/degree

    const SP_GET_RTDDASHBOARD_DETAILS = 'get_rtddashboard_details';
    const SP_GET_YARD_LIST = 'get_yard_list';
    const SP_GET_NOMENS = 'get_nomens';
    const SP_INSERT_NOMENS = 'insert_nomens';
    const SP_UPDATE_NOMENS = 'update_nomens';

    /* TEMPRATURE REPORT CC EMAIL ID*/
    const FERRERO_664_CC_EMAIL = "Tejilee.Tembe@ferrero.com";
    const SP_GET_DUPLICATE_UNITS_BY_VEHICLE_NO = "get_duplicate_units_by_vehicle_no";

    const SP_GET_SALES_USER = "get_sales_user";
    const SP_GET_DAILY_SALES_REPORT_DATA = "get_daily_sales_report_data";

    const SP_GET_SMS_CONSUME_FROM_SMSLOG_FOR_TEAM = "get_sms_consume_frm_smslog_for_team";
    const SP_GET_TEAM_SALES_DASHBOARD_DETAILS = "get_team_sales_dashboard_details";

    /*COMPANY ROLES FOR LOGIN*/
    const DISTRIBUTOR_COMP_ROLE = 22;

    // <editor-fold defaultstate="collapsed" desc="comqueue">
    const SP_INSERTQ = "InsertQ";
    const SP_GET_TEMP_ALERT_HISTORY = "getTempAlertHistory";
    //</editor-fold>

    /* TEAM ATTENDANCE APP AND WEB  */
    const SP_INSERT_TEAM_ATTENDANCE = "insert_team_attendance";
    const SP_GET_OFFICE_LOCATION = "get_office_locations";
    const SP_GET_TEAM_BUSY_STATUS = "get_attendance_busy_status";
    const SP_GET_ELIXIA_OFFICE_MEMBERS = "get_elixia_office_members";
    const SP_GET_ATTENDANCE_LOGS = "get_team_attendanceLogs";

    const SP_GET_SALES_USER_LIST = "get_sales_user_list";
    const SP_INSERT_SALES_TARGET_AMOUNT = "insert_sales_target_amount";
    const SP_GET_PRODUCT_LIST = "get_product_list";
    const SP_GET_SHOPS_BY_SR = "get_shops_by_sr";
    const SP_GET_SMS_STORE_LOG = "get_SMS_store_log";

    /* USER LOGS*/
    const SP_GET_USER_LOGS = "fetch_user_logs";
    const SP_GET_USER_STOPPAGE_ALERTS = "fetch_stoppage_alerts_logs";
    const SP_GET_USER_GROUP_MAPPING = "fetch_groupman_logs";
    /* USER LOGS*/

    const SP_CHECK_IF_EXIST_NOMENS = "check_if_exist_nomens";

    /* VEHICLE LOGS*/
    const SP_GET_VEHICLE_LOGS = "fetch_vehicle_logs";
    const SP_GET_CHECKPOINT_LOGS = "fetch_checkpoint_logs";
    const SP_GET_FENCE_LOGS = "fetch_fence_logs";
    const SP_GET_VEH_USER_LOGS = "fetch_vehicle_usermapping_logs";
    /* VEHICLE LOGS*/

    /* VEHICLE LOGS*/
    const SP_GET_GROUP_LOGS = "fetch_group_logs";
    /* VEHICLE LOGS*/

    /*DEVICE STATUS (TEAM) */
    const SP_GET_ALL_CUST_DEVICE_INFO = "get_all_customer_device_info";
    const SP_GET_ACTIVE_DEVICE_INFO = "get_active_device_info";
    const SP_GET_EXPIRED_DEVICE_INFO = "get_expired_device_info";
    const SP_GET_EXPIRING_DEVICE_INFO = "get_toBe_expired_device_info";
    /*DEVICE STATUS (TEAM) */

    /* UNIT LOGS*/
    const SP_GET_UNIT_LOGS = "fetch_unit_logs";
    /* UNIT LOGS*/

    const MDLZ_CUST_LIST = '644,524,646,206,353,613,523,674,643,632,729,116';
    const SP_MDLZ_DUMP_VEHICLE_DATA = 'mdlzDumpVehicleData';
    const SP_UPDATE_DUMP_SHIPMENT_NO = 'updateDumpShipmentno';

    const SP_GET_TRAVELSETTINGLIST = 'get_travelsetting_list';
    const SP_INSERT_TRAVEL_SETTINGS = 'insert_travel_settings';
    const SP_EDIT_TRAVEL_SETTINGS = 'edit_travel_settings';

    const SP_FETCH_CHECKPOINTWISE_USER = 'get_checkpoint_wise_users';

    const SP_UPDATE_ORDER_STATUS = 'update_order_status';

    /* SP constant defined for ROUTE_WISE_REPORT */
    const SP_FETCH_DATA_FOR_ROUTE_WISE_REPORT = 'fetch_data_for_route_wise_report';

    /*FETCH BLOGS FOR ELIXIATECH*/
    const SP_GET_BLOGS_PREVIEW = "fetch_blog_preview";
    const SP_GET_BLOGS_DETAILS = "fetch_blog_details";

    /*PROSPECTIVE CUSTOMERS*/
    const SP_INSERT_PROSP_CUST = "insert_prospectiveCust";
    const SP_GET_PROSP_CUSTOMERS = "get_prospectiveCust";
    const SP_UPDATE_PROSP_CUST = "update_prospectiveCust";
    const SP_DELETE_PROSP_CUST = "delete_prospectiveCust";

    /*Invoice Hold*/
    const SP_FETCH_INVOICE_HOLD_STATUS = "fetchInvoiceHoldCustomers";
    const SP_UPDATE_INVOICE_HOLD_STATUS = "update_invoiceHoldStatus";

    /*Custom Invoice Generation*/
    const SP_FETCH_CUSTOM_INVOICE_STATUS = "fetchCustomInvCustomers";

    /*Elixia Careers*/
    const SP_FETCH_ELIXIA_CAREERS = "fetch_elixia_careers";
    const SP_INSERT_ELIXIA_JOB_APPLICATION = "insert_job_application"; // Insert applicant's data
    const SP_UPDATE_ELIXIA_JOB_APPLICATION = "update_job_application"; //Update cv filename and loctn
    const SP_INSERT_JOB_OPENING = "insert_job_opening"; // Insert a job opening
    const SP_UPDATE_JOB_OPENING = "update_job_opening"; // Update a job opening
    const SP_DELETE_JOB_OPENING = "delete_job_opening";
    const SP_FETCH_JOB_APPLICANTS = "fetch_job_applicants";

    /*ELixia Tele Alerts*/
    const SP_GET_COMQUEUE_MESSAGE = 'get_comqueue_message';

    const SP_GET_OFFERS = 'get_offers';
    const SP_INSERT_ALERT_TEMP_USER_MAPPING = 'insertAlertTempUserMapping';
    const SP_INSERT_TEMP_SENSOR_SPECIFIC_ALERT = 'insertTempSensorSpecificAlert';

    /*** Insert checkpoint***/
    const SP_INSERT_CHECKPOINT = 'insertCheckPoint';
    const SP_GET_CUSTOMER_LIST = 'getCustomerList';
    const SP_ALLANASONS_TRANSPORTER = '984,1002,1003,1004,1005,1006,1007,1008,1009,1010,1011,1012,1013,1014,1015,1016,1017,1018,1019,1022,1033,1034,1035,1036,1037,1038,1040,1041,1042,110';

    /*Credit Note*/
    const SP_GET_INVOICE = "get_invoices";
    const SP_INSERT_CREDIT_NOTE = "insert_credit_note";
    const SP_UPDATE_CREDIT_NOTE = "update_credit_note";
     

    /* Payment Collection*/
    const SP_INSERT_PAYMENT_COLLECTION = "insert_payment_collection";
    const SP_GET_PAYMENT_COLLECTION = "get_payment_collection"; 
    const SP_EDIT_PAYMENT_COLLECTION = "update_payment_collection"; 
    const SP_DELETE_PAYMENT_COLLECTION = "delete_payment_collection";
    const SP_GET_COLLECTEDBY = "get_collectedBy";
}
