<table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:70%">
    <thead>
    <tr>
        <th>Group Name</th>
        <?php if($_SESSION['use_maintenance']=='1' && $_SESSION['use_hierarchy'] == '1' && $_SESSION['switch_to'] == '1'){ ?>
         <th>Group Code</th>        
        <th>Last Modified By</th>
        <th>Last Modified On</th> 
        <th colspan="2">Options</th>
        <?php } else{ ?>
        <th colspan="2">Options</th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
