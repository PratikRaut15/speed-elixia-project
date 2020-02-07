<table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:70%">
    <thead>
   <?php if($_SESSION['customerno']==64){ ?>
        <tr>
            <td width="100%" colspan="100%" style="text-align:right;">
                <a onclick="exportToMaintenanceUsers('xls')" href="javascript:void(0)">
                    <img class="exportIcons" title="Export to Excel" alt="Export to Excel" src="../../images/xls.gif">
                </a>
            </td>
        </tr>
        <?php } ?>
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
        <th colspan="4">Options</th>
        <?php }
        else{?>
        <th colspan="3">Options</th>
        <?php } ?>
         <?php if($_SESSION['use_maintenance'] == '1' && $_SESSION['switch_to']=='1')
            { ?>
        <th>Notes</th>        
        <th>History</th>
        <th>Documents</th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>