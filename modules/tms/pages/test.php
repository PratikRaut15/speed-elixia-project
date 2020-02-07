


<title>Example 1 - apply dataTable()</title>

<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.3/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">

<?php
$objDepot = new Depot();
    $objDepot->customerno = $_SESSION['customerno'];
    $objDepot->locationid = '';
    $objDepot->zoneid = '';
    $depots = get_depots($objDepot);
    $result = array("data" => $depots);
    
    //print_r($result);
//$depots = get_depots($objDepot);
?>






<table cellpadding="0" cellspacing="0" border="1" class="display" id="example1">
    <thead>
        <tr>
            <th>depotid</th>
        </tr>
    </thead>
    <tbody id="prec">
        <tr >
            <td></td>

        </tr>
    </tbody>            
    <tfoot>
    </tfoot>
</table>

<script type='text/javascript'>
    var data = <?php echo json_encode($depots);?>;
    var tableId= 'example1';
    var tableCols = [{"mData": "depotid"}];;
</script>