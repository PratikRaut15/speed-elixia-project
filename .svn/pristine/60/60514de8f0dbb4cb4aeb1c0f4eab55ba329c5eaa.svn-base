<table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:70%">
    <thead>
   
    <tr>
        <th>Sr. No</th>       
        <th><?php echo $vehicles_ses;?></th>
        <?php
        if($_SESSION['groupid'] != null)
        {
        ?>
            <th>Group</th>
        <?php
        }
        ?>        
        <th>Status</th>
        <th>Last Modified By</th>                            
        <th>Last Modified At</th>                    
        <?php if($_SESSION["role_modal"]=='elixir')
            { ?>
        <th colspan="3">Options</th>
        <?php }
        else{?>
        <th colspan="2">Options</th>
        <?php } ?>
         <?php if($_SESSION['use_maintenance'] == '1')
            { ?>
        <th>Notes</th>        
        <th>History</th>
        <th>Documents</th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>