<?php
$child_arr = array('sms' => 'off', 'email' => 'off', 'telephone' => 'off','mobile' => 'off', 'interval'=>0, 'stTime' => '00:00', 'edTime' => '23:59', 'veh' => 'all');
/*name-array in alerts.php*/
$parent_arr = array(
    'temp',
    'hum',
    'ignition',
    'speed',
    'ac',
    'powerc',
    'tamper',
    'harsh_break',
    'high_acce',
    'towing',
    'panic',
    'immob',
    'door'
);
/*column-names mapping user and vehiclewise_alert*/
$column_arr = array(
    'tempsms' => array('user','temp_sms'),
    'tempemail' => array('user','temp_email'),
    'temptelephone' => array('user','temp_telephone'),
    'tempmobile' => array('user','temp_mobilenotification'),
    'tempinterval' => array('user','tempinterval'),
    'humsms' => array('user','hum_sms'),
    'humemail' => array('user','hum_email'),
    'humtelephone' => array('user','hum_telephone'),
    'hummobile' => array('user','hum_mobilenotification'),
    'huminterval' => array('user','huminterval'),
    'ignitionsms' => array('user','ignition_sms'),
    'ignitionemail' => array('user','ignition_email'),
    'ignitiontelephone' => array('user','ignition_telephone'),
    'ignitionmobile' => array('user','ignition_mobilenotification'),
    'ignitioninterval' => array('user','igninterval'),
    'speedsms' => array('user','speed_sms'),
    'speedemail' => array('user','speed_email'),
    'speedtelephone' => array('user','speed_telephone'),
    'speedmobile' => array('user','speed_mobilenotification'),
    'speedinterval' => array('user','speedinterval'),
    'acsms' => array('user','ac_sms'),
    'acemail' => array('user','ac_email'),
    'actelephone' => array('user','ac_telephone'),
    'acmobile' => array('user','ac_mobilenotification'),
    'acinterval' => array('user','acinterval'),
    'powercsms' => array('user','power_sms'),
    'powercemail' => array('user','power_email'),
    'powerctelephone' => array('user','power_telephone'),
    'powercmobile' => array('user','power_mobilenotification'),
    'tampersms' => array('user','tamper_sms'),
    'tamperemail' => array('user','tamper_email'),
    'tampertelephone' => array('user','tamper_telephone'),
    'tampermobile' => array('user','	tamper_mobilenotification'),
    'harsh_breaksms' => array('user','harsh_break_sms'), 
    'harsh_breakemail' => array('user','harsh_break_mail'),
    'harsh_breaketelephone' => array('user','harsh_break_telephone'),
    'harsh_breakemobile' => array('user','harsh_break_mobilenotification'),
    'high_accesms' => array('user','high_acce_sms'), 
    'high_acceemail' => array('user','high_acce_mail'),
    'high_accetelephone' => array('user','high_acce_telephone'),
    'high_accemobile' => array('user','high_acce_mobilenotification'),
    'towingsms' => array('user','towing_sms'), 
    'towingemail' => array('user','towing_mail'),
    'towingtelephone' => array('user','towing_telephone'),
    'towingmobile' => array('user','towing_mobilenotification'),
    'panicsms' => array('user','panic_sms'), 
    'panicemail' => array('user','panic_email'),
    'panictelephone' => array('user','panic_telephone'),
    'panicmobile' => array('user','panic_mobilenotification'),
    'immobsms' => array('user','immob_sms'), 
    'immobemail' => array('user','immob_email'),
    'immobtelephone' => array('user','immob_telephone'),
    'immobmobile' => array('user','immob_mobilenotification'),
    'doorsms' => array('user','door_sms'), 
    'dooremail' => array('user','door_email'),
    'doortelephone' => array('user','door_telephone'),
    'doormobile' => array('user','door_mobilenotification'),
    'doorinterval' => array('user','doorinterval'),    
    'tempstTime' => array('alert','temp_starttime'),
    'tempedTime' => array('alert','temp_endtime'),
    
    'humstTime' => array('alert','hum_starttime'),
    'humedTime' => array('alert','hum_endtime'),
    
    'ignitionstTime' => array('alert','ignition_starttime'),
    'ignitionedTime' => array('alert','ignition_endtime'),
    'speedstTime' => array('alert','speed_starttime'),
    'speededTime' => array('alert','speed_endtime'),
    'acstTime' => array('alert','ac_starttime'),
    'acedTime' => array('alert','ac_endtime'),
    'powercstTime' => array('alert','powerc_starttime'),
    'powercedTime' => array('alert','powerc_endtime'),
    'tamperstTime' => array('alert','tamper_starttime'),
    'tamperedTime' => array('alert','tamper_endtime'),
    'harsh_breakstTime' => array('alert','harsh_break_starttime'),
    'harsh_breakedTime' => array('alert','harsh_break_endtime'),
    'high_accestTime' => array('alert','high_acce_starttime'),
    'high_acceedTime' => array('alert','high_acce_endtime'),
    'towingstTime' => array('alert','towing_starttime'),
    'towingedTime' => array('alert','towing_endtime'),
    'panicstTime' => array('alert','panic_starttime'),
    'panicedTime' => array('alert','panic_endtime'),
    'immobstTime' => array('alert','immob_starttime'),
    'immobedTime' => array('alert','immob_endtime'),
    'doorstTime' => array('alert','door_starttime'),
    'dooredTime' => array('alert','door_endtime'),
        
    'tempveh' => array('alert', 'vehicleid'),
    'humveh' => array('alert', 'vehicleid'),
    'ignitionveh' => array('alert', 'vehicleid'),
    'speedveh' => array('alert', 'vehicleid'),
    'acveh' => array('alert', 'vehicleid'),
    'powercveh' => array('alert', 'vehicleid'),
    'tamperveh' => array('alert', 'vehicleid'),
    'harsh_breakveh' => array('alert', 'vehicleid'),
    'high_acceveh' => array('alert', 'vehicleid'),
    'towingveh' => array('alert', 'vehicleid'),
    'panicveh' => array('alert', 'vehicleid'),
    'immobveh' => array('alert', 'vehicleid'),
    'doorveh' => array('alert', 'vehicleid'),
    
    'tempactive' => array('alert', 'temp_active'),
    'humactive' => array('alert', 'hum_active'),
    'ignitionactive' => array('alert', 'ignition_active'),
    'speedactive' => array('alert', 'speed_active'),
    'acactive' => array('alert', 'ac_active'),
    'powercactive' => array('alert', 'powerc_active'),
    'tamperactive' => array('alert', 'tamper_active'),
    'harsh_breakactive' => array('alert', 'harsh_break_active'),
    'high_acceactive' => array('alert', 'high_acce_active'),
    'towingactive' => array('alert', 'towing_active'),
    'panicactive' => array('alert', 'panic_active'),
    'immobactive' => array('alert', 'immob_active'),
    'dooractive' => array('alert', 'door_active'),
        
);

?>