<table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:80%">
    <thead>
   
    <tr>
        <th>Vehicle No</th>
        <th><?php echo($_SESSION['group']); ?></th>        
        <th>Status</th>
        <th>Approver</th>        
        <th>Sender</th>               
        <th>Date of Submission</th>  <?php           
            if($_SESSION["roleid"] == "1")
            { ?>             
        <th>Approve / Reject</th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>