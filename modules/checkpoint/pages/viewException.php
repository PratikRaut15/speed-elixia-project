<?php
$exceptions = getCheckpointExceptions();
?>
<table class="table  table-bordered table-striped dTableR dataTable"  style=" width:50%">
    <thead>

    <tr>
        <th>Sr. No.</th>
        <th>Exception</th>
        <th>Checkpoint</th>
        <th>Vehicle</th>

        <th>StartTime</th>
        <th>EndTime</th>
        <th>Exception Type</th>
        <!-- <th colspan="2">Options</th> -->
    </tr>
    </thead>
    <tbody>
        <?php
        if(isset($exceptions) && !empty($exceptions)) {
            $i = 1;
            foreach ($exceptions as $exception) {
                $tableRow  = "<tr>";
                $tableRow .= "<td>".$i++."</td>";
                $tableRow .= "<td>".$exception->exceptionName."</td>";
                $tableRow .= "<td>".implode(',<br />', $exception->checkpointList)."</td>";
                $tableRow .= "<td>".implode(',<br />', $exception->vehicleList)."</td>";
                $tableRow .= "<td>".$exception->startTime."</td>";
                $tableRow .= "<td>".$exception->endTime."</td>";
                $tableRow .= "<td>".$exception->exceptionTypeName."</td>";
                //$tableRow .= "<td><a href='#' ><i class='icon-pencil'></i></a></td>";
                /* $tableRow .= "<td><a href='checkpoint.php?id=3&chkid=<?php echo $exception->chkExpId;?>' ><i class='icon-pencil'></i></a></td>";*/
                //$tableRow .= "<td><a href = '#'><i class='icon-trash'></i></a></td>";
                /* $tableRow .= "<td><a href = 'route.php?Expdelid=<?php echo($exception->chkExpId); ?>' onclick='return confirm('Are you sure you want to delete?');'><i class='icon-trash'></i></a></td>";*/
                $tableRow .= "</tr>";
                echo $tableRow;
            }
        }else{
            echo "<tr><td colspan='7'>No Data</td></tr>";
        }
        ?>
    </tbody>
</table>

