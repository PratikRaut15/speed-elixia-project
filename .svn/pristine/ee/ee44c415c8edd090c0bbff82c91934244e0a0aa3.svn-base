<?php
/**
 * Timeline body interface
 */
$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];
$tlDate = isset($cdate) ? $cdate : date('d-m-Y');
$total_columns = ($max_hour-$start_hour)+1;
$cell_width = 100/$total_columns; //width(%) of 1 cell
$minute_width = $cell_width/60; //width(%) of 1 minute

$mm = new Mobility($customerno, $userid);
$timeline = $mm->get_timeline($tlDate);
$trackie = $mm->trackie_timeline($tlDate); //get info all trackie for displaying in timeline

$main_loop = 0;

$timestamp = strtotime($tlDate);
$day = date('l', $timestamp);

foreach($timeline as $single){
    $main_loop++;
    $tid = $single['tid'];
    $weeklyoff = $single['weeklyoff'];
    $week = explode(',', $weeklyoff);
    $week_name = array();
    
    foreach($week as $val){
        if(!empty($val) && isset($weeklyoff_arr[$val])){
            $week_name[] = $weeklyoff_arr[$val];
        }
    }
    
    $trcolor="";
    $holidaytxt ="";
    
    if(in_array($day, $week_name)){
        $trcolor = "class='select-row'"; 
        $holidaytxt = "<div class='holly'>Holiday</div>"; 
    }
    
    echo "<tr ".$trcolor.">";
    echo "<td id='{$single['tid']}'>{$single['tname'] }</td>";
    echo "<td colspan='$total_columns'>";
    echo $holidaytxt;
    $tr_data = array();
    $incre = 0;
    $min_start = ($start_hour*60);
    $min_end = ($max_hour*60)+60;
    $forloop = 1;
    for($min_loop=$min_start;$min_loop<$min_end;$min_loop++){ //minute loop
        $hr = floor($min_loop/60);
        $min = $min_loop-($hr*60);
        if(isset($trackie[$tid][$hr][$min])){
            $tracki = $trackie[$tid][$hr][$min];
            $incre++;
            $tr_data[$incre] = $tracki;
            $incre++;
            $min_loop = ($min_loop+$tracki['totaltime'])-1;
        }
        else{
            $tr_data[$incre] = isset($tr_data[$incre])  ? ($tr_data[$incre]+1) : 1;
        }
        
        $forloop++;
        
    }
    
    foreach($tr_data as $td){
        if(is_array($td)){
            $smin_dis = ($td['sminute']==0) ? "00" : $td['sminute']; //mins display
            if(strlen($smin_dis)==1){
                $smin_dis = "0$smin_dis";
            }
            $end = gmdate('H:i',((($td['shour']*60)+$td['sminute'])+$td['totaltime'])*60); //calculate end-time in hour:min
            $s_nd = "{$td['shour']}:$smin_dis-$end"; //time display
            $cell = "{$td['cname']}, $s_nd";
            $wd = $td['totaltime']*$minute_width;
            $sdate = date('D, d M, ', strtotime($td['sdate']))."$s_nd";
            $bg_color = isset($status_colors[$td['status']]) ? $status_colors[$td['status']][1] : '#E4EFF8';
            $color = isset($status_colors[$td['status']][2]) ? $status_colors[$td['status']][2] :'#000';
            $discount = "";
            if($td['discount']){
                if($td['discount']['isamount']==1){
                    $famount = $td['tcost']-$td['discount']['val'];
                }
                else{
                    $famount = ($td['discount']['val']*$famount)/100;
                    $famount = $td['tcost']-round($famount,2);
                }
                
                $discount = "Discount: {$td['discount']['val']} {$td['discount']['unit']};<br/>Final Amount: $famount Rs";
            }
            
            //echo "<div class='allDataCell draggable' id='{$td['scid']}' style='background-color:$bg_color;color:$color;width:$wd%;' 
            //onmousedown='if(event.which==3){statusChange(\"$sdate\", \"{$td['services']}\", \"{$td['mobile']}\", \"{$td['cname']}\", {$td['status']}, {$td['scid']}, \"{$td['address']}\", \"{$td['tcost']}\",  \"{$td['totaltime']}\", \"$discount\");}'>$cell</div>";
            
            echo "<div class='allDataCell draggable' id='{$td['scid']}' style='background-color:$bg_color;color:$color;width:$wd%;' 
            onmousedown='if(event.which==3){statusChange(\"$sdate\", \"{$td['services']}\", \"{$td['mobile']}\", \"{$td['cname']}\", {$td['scid']}, \"{$td['address']}\", \"{$td['tcost']}\",  \"{$td['totaltime']}\", \"$discount\");}'>$cell</div>";
        }
        else{
            $wd = $td*$minute_width;
            echo "<div style='width:$wd%;float:left;' class='droppable mainTds' >&nbsp;</div>";
        }
    }
    
    echo "</td>";
    echo "</tr>";
}
?>
<script>
    var starthr = <?php echo $start_hour; ?>;
    var minuteWidth = <?php echo $minute_width; ?>;
</script>
