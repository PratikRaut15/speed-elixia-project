<?php
set_time_limit(0);
date_default_timezone_set("Asia/Calcutta");
ini_set('display_errors', 'On');
error_reporting(E_ALL);

include_once "../../lib/bo/CustomerManager.php";

$default_sytem =  'linux';
$path = '/var/www/html/speed/customer/';
//$default_sytem = 'windows';
//$path = 'C:\xampp\htdocs\ak_test\customer\\'; 
$day = "2014-10-30";

class delete_file{
    
    var $cmd_delete;
    var $unitno_p;
    var $day;
    
    function __construct($customer_id, $system, $path, $day) {
        $this->customer_id = $customer_id;
        $this->system = $system;
        $this->server_path = $path;
        $this->s_c = $this->server_path.$this->customer_id;
        $this->day = $day;
    }

    function validate(){
        if(!$this->customer_id){ echo "Customer id is invalid<br/>";return false;}
        if(!(is_dir($this->s_c))){ print "Directory does not exist<br/>"; return false; }
        return true;
    }
    
    function set_command(){
        if($this->system=='linux'){
            $this->cmd_delete = "rm ";
            
            $this->unitno_p = $this->s_c."/unitno/";
            $this->unitno_p_sub_pdf = $this->unitno_p."#/pdf/";
            $this->unitno_p_sub_csv = $this->unitno_p."#/csv/";
            $this->reports_csv = $this->s_c."/reports/csv/";
            $this->reports_pdf = $this->s_c."/reports/pdf/";
        }
        else{
            $this->cmd_delete = "del ";
            
            $this->unitno_p = $this->s_c."\\unitno\\";
            $this->unitno_p_sub_pdf = $this->unitno_p."#\\pdf\\";
            $this->unitno_p_sub_csv = $this->unitno_p."#\\csv\\";
            $this->reports_csv = $this->s_c."\\reports\csv\\";
            $this->reports_pdf = $this->s_c."\\reports\pdf\\";
        }
    }
    
    function execute_remove_command(){
        $removed = '';
        
        /*unitno starts*/
        if(is_dir($this->unitno_p)){
        $folders_present = scandir($this->unitno_p);
        
        foreach($folders_present as $single_folder){
            $full_path = str_replace('#', $single_folder, $this->unitno_p_sub_pdf);
            $removed .= $this->remove_old_files($full_path, 'pdf', '_', 'unitno/num/pdf');
            
            $full_path = str_replace('#', $single_folder, $this->unitno_p_sub_csv);
            $removed .= $this->remove_old_files($full_path, 'xls', '_', 'unitno/num/csv');
        }
        }
        /*unitno ends*/
        
        /*reports starts*/
        $removed .= $this->remove_old_files($this->reports_csv, 'xls', '_summaryreport.xls');
        $removed .= $this->remove_old_files($this->reports_pdf, 'pdf', '_summaryreport.pdf');
        /*reports ends*/
        
        return $removed.'<br/>';
    }
    
    function remove_old_files($full_path, $ext, $replace, $replace_decider=''){
        $date_limit = $this->day;
        
        if(is_dir($full_path)){
            $files_present = scandir($full_path);
            $c=0;
            foreach($files_present as $single_file){
                if(preg_match("/.$ext$/", $single_file)){
                    if($replace_decider=="unitno/num/pdf" || $replace_decider=="unitno/num/csv"){
                        $file_date = explode($replace, $single_file);
                        $file_date = $file_date[1];
                    }
                    else{
                        $file_date = str_replace($replace, '', $single_file);
                    }
                    
                    $file_date = date('Y-m-d', strtotime($file_date));
                    if($file_date==$date_limit){
                        if(preg_match('/\s*/', $single_file)){
                            if($this->system=='linux'){
                                $cmd = $this->cmd_delete.' "'.$full_path.$single_file.'"';
                            }
                            else{
                                $single_file = str_replace(' ','*',$single_file);
                                $cmd = $this->cmd_delete.' /F "'.$full_path.$single_file.'"';
                            }
                        }
                        else{
                            $cmd = $this->cmd_delete." ".$full_path.$single_file;
                        }
                        exec($cmd);
                        $c++;
                        //echo $c.'==='.$full_path.$single_file.'<br/>';
                    }
                }
            }
            $return = "Folder: ".$full_path.", $ext Files deleted: $c<br/>";
            return $return;
        }
    }
    
}
$customer = new CustomerManager();
$customer_ids = $customer->getcustomernos();
        
foreach($customer_ids as $customer_id){
    $customer_id = (int) $customer_id;
    echo "============================$customer_id========================<br/>";
    $obj = new delete_file($customer_id, $default_sytem, $path, $day);
    
    if(!$obj->validate()){
        continue;echo "<hr>";
    }
    $obj->set_command();
    echo $obj->execute_remove_command();
    echo "<hr>";
}

?>