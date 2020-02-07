<?php
set_time_limit(0);
date_default_timezone_set("Asia/Calcutta");
//ini_set('display_errors', 'On');
//error_reporting(E_ALL);

$customer_id = isset($_GET['customerno'])?$_GET['customerno']:'';
$customer_id = (int) trim($customer_id);
$deletezip = isset($_GET['deletezip'])?$_GET['deletezip']:false;

$default_sytem =  'linux';
$path = '/var/www/html/speed/customer/';
//$default_sytem = 'windows';
//$path = 'C:\xampp\htdocs\ak_test\\'; 
$months = 2;

class backup_file{
    
    var $cmd_zip;
    var $cmd_move;
    var $cmd_delete;
    var $unitno_p;
    
    function __construct($customer_id, $system, $path, $months) {
        $this->customer_id = $customer_id;
        $this->system = $system;
        $this->server_path = $path;
        $this->s_c = $this->server_path.$this->customer_id;
        $this->validate();
        $this->months_limit = $months;
        $this->date_ext = $this->customer_id."_".date('M_d_Y').".zip";
    }

    function validate(){
        if(!$this->customer_id){ echo "Customer id is invalid<br/>";exit;}
        if(!(is_dir($this->s_c))){ print "Directory does not exist<br/>"; exit; }
    }
    
    function set_command(){
        if($this->system=='linux'){
            $this->cmd_zip = "zip -r  {$this->server_path}{$this->date_ext} $this->s_c";
            $this->cmd_move = "mv {$this->server_path}{$this->date_ext} {$this->s_c}/{$this->date_ext}"; 
            $this->cmd_delete = "rm ";
            $this->delete_zip_path = "$this->s_c/".$this->date_ext;
            
            $this->unitno_p = $this->s_c."/unitno/";
            $this->unitno_p_sub_s = $this->unitno_p."#/sqlite/";
            $this->unitno_p_sub_pdf = $this->unitno_p."#/pdf/";
            $this->unitno_p_sub_csv = $this->unitno_p."#/csv/";
            $this->history = $this->s_c."/history/";
            $this->reports_csv = $this->s_c."/reports/csv/";
            $this->reports_pdf = $this->s_c."/reports/pdf/";
        }
        else{
            $this->cmd_zip = "xcopy {$this->s_c} {$this->server_path}{$this->date_ext}_zipped /s /i";
            $this->cmd_move = "move {$this->server_path}{$this->date_ext}_zipped {$this->s_c}\\{$this->date_ext}_zipped";
            $this->cmd_delete = "del ";
            $this->delete_zip_path = "$this->s_c\\".$this->date_ext.".txt";
            
            $this->unitno_p = $this->s_c."\\unitno\\";
            $this->unitno_p_sub_s = $this->unitno_p."#\\sqlite\\";
            $this->unitno_p_sub_pdf = $this->unitno_p."#\\pdf\\";
            $this->unitno_p_sub_csv = $this->unitno_p."#\\csv\\";
            $this->history = $this->s_c."\\history\\";
            $this->reports_csv = $this->s_c."\\reports\csv\\";
            $this->reports_pdf = $this->s_c."\\reports\pdf\\";
        }
    }
    
    function execute_backup_command(){
        $zipped = exec($this->cmd_zip); 
        $moved = exec($this->cmd_move); 
        return $zipped."<br/>".$moved."<br/>";
    }
    
    function execute_remove_command(){
        $removed = '';
        $date_limit = date('Y-m-d', strtotime("-$this->months_limit months"));
        
        /*unitno starts*/
        $folders_present = scandir($this->unitno_p);
        foreach($folders_present as $single_folder){
            $full_path = str_replace('#', $single_folder, $this->unitno_p_sub_s);
            $removed .= $this->remove_old_files($full_path, 'sqlite', '.sqlite');
            
            $full_path = str_replace('#', $single_folder, $this->unitno_p_sub_pdf);
            $removed .= $this->remove_old_files($full_path, 'pdf', '_', 'unitno/num/pdf');
            
            $full_path = str_replace('#', $single_folder, $this->unitno_p_sub_csv);
            $removed .= $this->remove_old_files($full_path, 'xls', '_', 'unitno/num/csv');
        }
        /*unitno ends*/
        
        /*history starts*/
        $removed .= $this->remove_old_files($this->history, 'sqlite', '.sqlite');
        /*history ends*/
        
        /*reports starts*/
        $removed .= $this->remove_old_files($this->reports_csv, 'xls', '_summaryreport.xls');
        $removed .= $this->remove_old_files($this->reports_pdf, 'pdf', '_summaryreport.pdf');
        /*reports ends*/
        
        return $removed.'<br/>';
    }
    
    function remove_old_files($full_path, $ext, $replace, $replace_decider=''){
        $date_limit = date('Y-m-d', strtotime("-$this->months_limit months"));
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
                    if($file_date<$date_limit){
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
    
    function remove_zip($deletezip){
        if($deletezip == "true" && $this->customer_id){
            if(file_exists($this->delete_zip_path)){
            $cmd = "$this->cmd_delete $this->delete_zip_path";
            $return = exec($cmd);
            return "Zip File Deleted<br/>";
            }
            else{
                return "Zip File does not exist<br/>";
            }
        }
    }
}

$obj = new backup_file($customer_id, $default_sytem, $path, $months);
$obj->set_command();
if($deletezip != 'true')
{
    echo $obj->execute_backup_command();    
    echo $obj->execute_remove_command();
}
echo $obj->remove_zip($deletezip);

?>