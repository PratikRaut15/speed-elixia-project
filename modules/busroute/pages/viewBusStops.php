<?php
echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/datatables/jquery.dataTables_new.css" type="text/css" />';
echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
?>
<style type="text/css">
    #busStopDetails_filter{display: none}
   .busStopDetails_length{display: none}
</style>
<div class="container-fluid">
    <table class='display table table-bordered table-disable-hover select' id="busStopDetails" style="width:90%">
        <thead>
            <tr class="filterrow">
                <td><input type="text" class='input-filter' name='' style="width:90%;" /></td>
                <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
                <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            </tr>
            <tr>
                <th data-sortable="true">Bus Stop ID</th>
                <th data-sortable="true">Name</th>
                <th data-sortable="true">Address</th>
                <th data-sortable="true">Students</th>
                <th data-sortable="true">Distance From School (In Kms)</th>
                <th data-sortable="true">Zone</th>
            </tr>
        </thead>
    </table>
</div>
