<?php
//error_reporting(0);
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/bo/TeamManager.php");

class customers {
    
}

$tm=new TeamManager();
  
$max_invoice_id = array();
$max_invoice_id = $tm->getMaxInvoiceId_Budgeting();

$customersArray = array();
foreach($max_invoice_id as $invoice_id){
$customersArray[]=$tm->getCustomer_Budgeting($invoice_id);
}
$customerzz = array();
$budgetObj = new stdClass();

    foreach($customersArray as $data){ 
    
        $customer['ledgername']=$data['ledgername'];
        $customer['ledgerid']=$data['ledgerid'];
        $customer['renewal']=$data['renewal'];
        $customer['customerno']=$data['customerno'];
        $customer['customername']=$data['customercompany'];
        $renewal_number=intval($data['renewal_number']);
        if($renewal_number==-3){
             $customer['unit_msp']=intval($data['lease_price'])+intval($data['warehouse_msp']);
             $unit_msp = $customer['unit_msp'];
        }
        else{
            $customer['unit_msp']=intval($data['unit_msp'])+intval($data['warehouse_msp']);
            $unit_msp = $customer['unit_msp'];
        }
        $customer['devices_count']=intval($data['devices_count']);
        $customer['fixed_month_price']=$unit_msp*$data['devices_count'];
        $customer['manager_name']=$data['name'];
        $customer['start_date']=$data['start_date'];
        $customer['end_date']=$data['end_date'];
        $customer['max_invoice_id']=$data['invoiceidParam'];

        $budgetObj->ledgerid = $customer['ledgerid'];
        $budgetObj->customerno = $customer['customerno'];
        $budgetObj->start_date = $customer['start_date'];
        $budgetObj->end_date = $customer['end_date'];
        $budgetObj->max_inv_id = $customer['max_invoice_id'];

        $fixedBudgetArray = $tm->getFixedBudget($budgetObj);

        $start    = new DateTime($budgetObj->start_date);
        $start->modify('first day of this month');
        $end      = new DateTime($budgetObj->end_date);
        $end->modify('first day of next month');
        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($start, $interval, $end);

        foreach ($period as $dt){

                    $months[] =$dt->format("Y-M");  
        } 
        if(empty($months)){
            print_r($customer['customerno']);
        }
        $start1    = new DateTime('2018-04-01');
        $start1->modify('first day of this month');
        $end1      = new DateTime($budgetObj->end_date);
        $end1->modify('2019-03-31');
        $interval1 = DateInterval::createFromDateString('1 month');
        $period1   = new DatePeriod($start1, $interval1, $end1);

        foreach ($period1 as $dts){
                
                    $months_fixed_year[] =$dts->format("Y-M");  
        } 
            
        $difference_months_previous = array();
        $difference_months_after = array();
        $intersect_months = array();

        $difference_months_previous_new = array();
        $difference_months_after_new = array();
        $intersect_months_new = array();

        $intersect_months = array_intersect($months_fixed_year,$months);

         // echo '<pre>';
         // print_r($months);
         // print_r($months_fixed_year);
         

        foreach($intersect_months as $i_sec){
            if(strtotime($i_sec)>='2018-Apr' && strtotime($i_sec)<='2019-Mar'){
                 $i_sec1 = $i_sec;
                $intersect_months[] = $i_sec1;
            }
        }
        //print_r($intersect_months);
        
        $first_value_array = current($intersect_months);
        $last_value_array = end($intersect_months);

        $first_value_array = date('Y-M', strtotime($first_value_array));
        $last_value_array =  date('Y-M', strtotime($last_value_array));

        foreach($months_fixed_year as $fixed){
           
             if(strtotime($fixed)<strtotime($first_value_array)){
                $difference_months_previous[]=$fixed;
             }
             if(strtotime($fixed)>strtotime($last_value_array)){
                $difference_months_after[]=$fixed;
             }   
        }
        // echo '<pre>';
        // print_r($intersect_months);
        // print_r($difference_months_previous);
        // print_r($difference_months_after);
        // die();
        $intersect_months=array_flip($intersect_months);

        if(!empty($difference_months_previous)){

            $difference_months_previous=array_flip($difference_months_previous);
        }

        $difference_months_after=array_flip($difference_months_after);
        

        // echo '<pre>';
        // print_r($intersect_months);
        // print_r($difference_months_previous);
        // print_r($difference_months_after);
        // die();
        if(!empty($intersect_months)){
            foreach($intersect_months as $i=>$value){
                if(isset($fixedBudgetArray['final_amt'])){
                 $intersect_months[$i] =intval($fixedBudgetArray['final_amt']);
                }
                else{
                    $intersect_months[$i] = $customer['fixed_month_price'];
                }
            }
        }

        if(!empty($difference_months_previous)){
            foreach($difference_months_previous as $i=>$value){
                $difference_months_previous[$i] =0;
            }
        }

        if(!empty($difference_months_after)){
            foreach($difference_months_after as $i=>$value){
                $difference_months_after[$i] =$customer['fixed_month_price'];
            }
        }
        
        // echo '<pre>';
        // print_r($intersect_months);
        // print_r($difference_months_previous);
        // print_r($difference_months_after);
        // die();
        foreach($intersect_months as $key=>$tag){
             $month = date('M', strtotime($key));
             $intersect_months_new[$month]=$tag;
        }
        foreach($difference_months_previous as $key=>$tag){
             $month = date('M', strtotime($key));
             $difference_months_previous_new[$month]=$tag;
        }
        foreach($difference_months_after as $key=>$tag){
             $month = date('M', strtotime($key));
             $difference_months_after_new[$month]=$tag;
        }
        
        // echo '<pre>';
        // print_r($intersect_months_new);
        // print_r($difference_months_previous_new);
        // print_r($difference_months_after_new);
        // die();
        $months_data = array();
        $months_data=array_merge($intersect_months_new,$difference_months_previous_new,$difference_months_after_new);
        $months_data = array_merge($customer,$months_data);
        $customers[] = $months_data;

        unset($fixedBudgetArray);
        unset($months);
        unset($months_fixed_year);
    }    
// echo '<pre>';
// print_r($customers);
// die();
include("header.php");
?>
<style>
#myDiv {
  display: none;
  text-align: center;
}
.ag-row-footer{
    background-color: #8b9dc3 !important;
    color:#fff !important;
    font-weight: 600;
}
</style>

<br/>
    <h3 style="margin:0 40%;">BUDGETING</h3>
    <div class="panelcontents">
        <div id="myGrid" class="ag-theme-fresh" style="height:500px;width:100%;margin:0 auto;border: 1px solid gray"></div>
    </div>

<br/>

<?php
include("footer.php");
?>

    <script src="https://unpkg.com/ag-grid-enterprise@18.1.1/dist/ag-grid-enterprise.min.js"></script>
<script>

    agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
    var details = <?php echo json_encode($customers)?>;
    //console.log(details);
    var gridOptions;
    columnDefs = [
        // {headerName: 'Delete', cellRenderer:'deleteCellRenderer',width: 80},
       
        {headerName:'Customer No',field: 'customerno',width:120,filter: 'agTextColumnFilter'},
        {headerName:'Customer Company',field: 'customername',width: 150,filter: 'agTextColumnFilter'},
        {headerName:'Ledger ID', field:'ledgerid',width: 120,filter: 'agTextColumnFilter'},
        {headerName:'Ledger Name',field: 'ledgername',width: 150, filter: 'agTextColumnFilter'},
        {headerName:'CRM',field: 'manager_name',width:100,filter: 'agTextColumnFilter'},
        {headerName:'Subs Period',field: 'renewal',width:150,filter: 'agTextColumnFilter'},
        {headerName:'Unit MSP',field: 'unit_msp',width:100,aggFunc: 'sum',filter: 'agNumberColumnFilter'},
         {headerName:'Device Count',field: 'devices_count',width:100,aggFunc: 'sum',filter: 'agNumberColumnFilter'},
        {headerName:'Opening Balance',field: 'opening_bal',aggFunc: 'sum',width:150,filter: 'agTextColumnFilter'},
        {headerName:'April',field: 'Apr',colId:'a',width:150, aggFunc:'sum', filter: 'agTextColumnFilter'},
        {headerName:'May',field: 'May',colId:'b',width:150, aggFunc: 'sum',filter: 'agTextColumnFilter'},
        {headerName:'June',field: 'Jun',width:150, aggFunc: 'sum',filter: 'agTextColumnFilter'},
        {headerName:'July',field: 'Jul',width:150, aggFunc: 'sum',filter: 'agTextColumnFilter'},
        {headerName:'August',field: 'Aug',width:150, aggFunc: 'sum',filter: 'agTextColumnFilter'},
        {headerName:'September',field: 'Sep',width:150, aggFunc: 'sum',filter: 'agTextColumnFilter'
        },
        {headerName:'October',field: 'Oct',width:150, aggFunc: 'sum',filter: 'agTextColumnFilter'},
        {headerName:'November',field: 'Nov',width:150, aggFunc: 'sum',filter: 'agTextColumnFilter'},
        {headerName:'December',field: 'Dec',width:150, aggFunc: 'sum',filter: 'agTextColumnFilter'},
        {headerName:'January',field: 'Jan',width:150, aggFunc: 'sum',filter: 'agTextColumnFilter'},
        {headerName:'February',field: 'Feb',width:150, aggFunc: 'sum',filter: 'agTextColumnFilter'},
        {headerName:'March',field: 'Mar',width:150, aggFunc: 'sum',filter: 'agTextColumnFilter'},
        {headerName: "Total",width:200,SupressFilter:true,aggFunc: 'sum',colId: 'Apr&May&Jun&Jul&Aug&Sep&Oct&Nov&Dec&Jan&Feb&Mar',
        valueGetter: function aPlusBValueGetter(params) {
            return params.data.Apr + params.data.May +
            params.data.Jun + params.data.Jul +
            params.data.Aug + params.data.Sep +
            params.data.Oct + params.data.Nov +
            params.data.Dec + params.data.Jan +
            params.data.Feb + params.data.Mar;
        }}
    ];

    gridOptions = {
        enableFilter:true,
        enableSorting: true,
        floatingFilter:true,
        rowSelection: 'single',
        rowData:details,
        animateRows:true,
        columnDefs: columnDefs,
        groupIncludeFooter: true,
        groupIncludeTotalFooter: true
    };

    var gridDiv = document.getElementById('myGrid');
    new agGrid.Grid(gridDiv,gridOptions);
</script>
