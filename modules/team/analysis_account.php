<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/Date.php");

class Account{
}
$db = new DatabaseManager();
$customernos=Array();
    function getcustomer_detail() {
        $db = new DatabaseManager();
        $customer = Array();
        $SQL = sprintf("SELECT customerno,customername,customercompany FROM ".DB_PARENT.".customer");
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                //$customer = new Receive();
            $customer['customerno'] = $row['customerno'];
            $customer['customername'] = $row['customername'];
            $customer['customercompany'] = $row['customercompany'];
                $customernos[] = $customer;
            }
            return $customernos;
            //print_r($customernos);
            }
        return false;
    }
include("header.php");
    ?>
    <!--<div style='float:left'>
        <div style='float:left;'>Account Summary</div><br>
            <div style='float:left;'>
            <table>
                <thead>
                    <tr><th>Total Receivables</th></tr>
                    <tr><th>Total Petty Cash</th></tr>
                </thead>
                <tbody>
                    <tr>
                    <?php /*
                    if(isset($overspeed_data['top_speed'])){ 
                        $top_speed = $overspeed_data['top_speed'];
                        echo  "<td>".$top_speed[0]."</td><td><b>".$top_speed[1]."</b></td><td>".$top_speed[2]."</td><td>".$top_speed[3]."</td>";
                    }
                    else{
                        echo '<td colspan="4">No Data</td>';
                    } */
                    ?>
                    </tr>
                </tbody>
                    
            </table>
            </div>
    </div>-->
            <?php
$cust_no = getcustomer_detail();   
$pend_amt=Array();
$petty_amt=Array();
$paid_amt=Array();
if(isset($cust_no)){
    foreach($cust_no as $thiscustomerno)
    {
       // print_r($thiscustomerno);
//die();
        $SQL1 = sprintf("SELECT sum(pending_amt) as pending_amount FROM ".DB_PARENT.".invoice WHERE customerno = %d",$thiscustomerno['customerno']);
    $db->executeQuery($SQL1);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow())
        {
             $pamt['pending']= $row["pending_amount"];
             //if($row["pending_amount"]==""){
             //$pamt['pending']=0;    
             //}
             $pend_amt[$thiscustomerno['customerno']] = $pamt;
        }
    }
    
    $SQL2 = sprintf("SELECT customer,sum(amount) AS total FROM ".DB_PARENT.".`voucher`
                        WHERE ispaid =1 AND customer=%d
                        GROUP BY customer",$thiscustomerno['customerno']);
                      
    $db->executeQuery($SQL2);
    if ($db->get_rowCount() > 0) {
        while ($row1 = $db->get_nextRow())
        {
             $paidcash['pcash']= $row1["total"];
             //if($row1["total"]==""){
             //$paidamt['paid']=0;    
             //}
             $petty_amt[$thiscustomerno['customerno']]=$paidcash;
        }
    
             }

    $SQL3 = sprintf("SELECT sum(paid_amt) as paid_amount FROM ".DB_PARENT.".invoice WHERE customerno = %d",$thiscustomerno['customerno']);
    $db->executeQuery($SQL3);
    if ($db->get_rowCount() > 0) {
        while ($row2 = $db->get_nextRow())
        {
             $pdamt['paid']= $row2["paid_amount"];
             //if($row["pending_amount"]==""){
             //$pamt['pending']=0;    
             //}
             $paid_amt[$thiscustomerno['customerno']] = $pdamt;
        }
    }
        }
} 
//echo "<pre>";print_r($pend_amt);die();
$cus = array();
$pending = array();
$petty = array();
$pay=array();
//$pencent='';
//$pamnt=array();
//$pdamnt=array();
foreach($cust_no as $thiscustomerno){
    $customerno = $thiscustomerno['customerno'];
    $pamnt =  (int)$pend_amt[$customerno]['pending'];
    $pdamnt = (int)$petty_amt[$customerno]['pcash'];
    $paidamnt =(int)$paid_amt[$customerno]['paid'];
    if($paidamnt=="0" || $pdamnt=="0")
       {
            $percent=0;
            $formatted_number = number_format((float)$percent, 2, '.', '');
        }
        else{
                $percent = ($pdamnt / $paidamnt) * 100;
                $formatted_number = number_format((float)$percent, 2, '.', '');
        }
    if($pamnt=="0" && $pdamnt=="0"){
        continue;
    }
    $cus[] = "'$customerno ($formatted_number %)'"; 
    $pending[] = $pamnt;
    $petty[] = $pdamnt;
    $pay[] =$paidamnt;
    //die();
}
//echo"<pre>";print_r($pamnt);
//die();



  
            ?>
            <!--<div id="container_inv_veh" style="min-width: 910px; min-height: <?php //echo $top_speed_height; ?>px; margin: 0 auto;"></div>
                    </div><br clear='both'><hr>-->
            <div id="container" style="min-width: 910px; min-height:4000px; margin: 0 auto;"></div>
<script src="../../scripts/highcharts/js/highcharts.js" type='text/javascript'></script>
<script type="text/javascript">
    
jQuery(function () { 
    jQuery('#container').highcharts({
        chart: {
            type: 'line',
            inverted: true
        },
        title: {
            text: 'Account Analysis'
        },
        xAxis: {
                title: {
                text: 'Customer Nos.'
                },
                categories: [<?php echo join($cus, ',');?>] //"90, 67, 5, 7, 45, 34"//
            //categories: [<?php //echo "1, 2, 3, 4, 5, 6";?>], //"90, 67, 5, 7, 45, 34"//
            
        },
        yAxis: {
            min :0,
            title: {
                text: 'Amount in Rs.'
            },
            
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: 0,
            y: 62,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },  
        colors: ['#FF0000','#000000','#008000'],        
        series: [{
            name: 'Receivables',
            data: [<?php echo join($pending, ',');?>]
        },{
            name: 'Petty Cash',
            data: [<?php echo join($petty, ',');?>]
        },{
            name: 'Paid Amount',
            data: [<?php echo join($pay, ',');?>]
        }]
         
    });
});    

</script>