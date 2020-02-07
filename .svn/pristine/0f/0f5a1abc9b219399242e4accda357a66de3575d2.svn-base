<?php
echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/datatables/jquery.dataTables_new.css" type="text/css" />';
?>
<table class='display table table-bordered table-striped'  id="viewstudents" style="width:90%">
    <thead>
       <tr class="filterrow">
            <td><input type="hidden" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>
            <td><input type="text" class='input-filter' name='' style="width:90%;" autocomplete="off"/></td>


        </tr>
        <tr>
            <th data-sortable="true">StudentId</th>
            <th data-sortable="true">FirstName</th>
            <th data-sortable="true">Surname</th>
            <th data-sortable="true">EnrollmentNo</th>
            <th data-sortable="true">CenterId</th>
            <th data-sortable="true">Board</th>
            <th data-sortable="true">Grade</th>
            <th data-sortable="true">Division</th>
            <th data-sortable="true">Address</th>
            <th data-sortable="true">Bus Stop</th>


<!--        </tr>
    </thead>
    <tfoot>
        <tr>
            <th data-sortable="true">StudentId</th>
            <th data-sortable="true">StudentName</th>
            <th data-sortable="true">EnrollmentNo</th>
            <th data-sortable="true">CenterId</th>
            <th data-sortable="true">Board</th>
            <th data-sortable="true">Grade</th>
            <th data-sortable="true">Division</th>
            <th data-sortable="true">Address</th>
            <th data-sortable="true">Building</th>
            <th data-sortable="true">Street</th>
            <th data-sortable="true">Landmark</th>
            <th data-sortable="true">Area</th>
            <th data-sortable="true">Station</th>
            <th data-sortable="true">City</th>
            <th data-sortable="true">Pincode</th>-->
        </tr>
    </thead>
</table>
        <?php
//        $students = getstudents();
//        if (isset($students)) {
////            $x = 1;
//            foreach ($students as $student) {
//                echo "<tr>";
////                echo "<td>$x</td>";
//                echo "<td>" . $student->studentId . "</td>";
//                echo "<td>" . $student->studentName . "</td>";
//                echo "<td>" . $student->enrollmentNo . "</td>";
//                echo "<td>" . $student->centerId . "</td>";
//                echo "<td>" . $student->board . "</td>";
//                echo "<td>" . $student->grade . "</td>";
//                echo "<td>" . $student->division . "</td>";
//                echo "<td>" . $student->address . "</td>";
//                echo "<td>" . $student->building . "</td>";
//                echo "<td>" . $student->street . "</td>";
//                echo "<td>" . $student->landmark . "</td>";
//                echo "<td>" . $student->area . "</td>";
//                echo "<td>" . $student->station . "</td>";
//                echo "<td>" . $student->city . "</td>";
//                echo "<td>" . $student->pincode . "</td>";
//                echo "</tr>";
////                $x++;
//            }
//        } else {
//            echo "<tr>
//                    <td colspan=100%>No Part Created</td>
//                 </tr>";
//        }
        ?>
    <!--</tbody>
</table>-->
<!--<form style="width: 48%;" class="simple_form adminform form-horizontal" id="add_part" name="add_part" action="route.php" method="POST">
    <span id="problem" style="display: none">Please enter name</span>
    <div class="control-group string required"><label class="string required control-label" for="service_part_name"><abbr title="required">*</abbr> Name</label><div class="controls"><input class="string required" id="partname" name="partname" size="50" type="text" autofocus=""><p class="help-block">Type of service part used during a vehicle service</p></div></div>
    <div class="form-actions">
        <input type="button" name="adduserdetails" class="btn  btn-primary" value="Add part" onclick="submitpart();">
    </div>
</form>-->
<style>
    table.dataTable.select tbody tr,
    table.dataTable thead th:first-child {
        cursor: pointer;
    }
    .table-disable-hover.table tbody tr:hover td,
    .table-disable-hover.table tbody tr:hover th {
        background-color: inherit;
    }
    .table-disable-hover.table-striped tbody tr:nth-child(odd):hover td,
    .table-disable-hover.table-striped tbody tr:nth-child(odd):hover th {
        background-color: #f9f9f9;
    }
    .lengthMenu{
        margin: auto;
        width: 10%;
    }
    /*    .form-horizontal .control-label {
            float: left;
            width: 160px;
            padding-top: 5px;
            text-align: right;
        }
        .adminform {
            padding: 7px 14px;
            margin: 10px 0 10px;
            list-style: none;
            background-color: #fbfbfb;
            background-image: -moz-linear-gradient(top, #fff, #f5f5f5);
            background-image: -ms-linear-gradient(top, #fff, #f5f5f5);
            background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#fff), to(#f5f5f5));
            background-image: -webkit-linear-gradient(top, #fff, #f5f5f5);
            background-image: -o-linear-gradient(top, #fff, #f5f5f5);
            background-image: linear-gradient(top, #fff, #f5f5f5);
            background-repeat: repeat-x;
            border: 1px solid #ddd;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            filter: progid:dximagetransform.microsoft.gradient(startColorstr='#ffffff', endColorstr='#f5f5f5', GradientType=0);
            -webkit-box-shadow: inset 0 1px 0 #ffffff;
            -moz-box-shadow: inset 0 1px 0 #ffffff;
            box-shadow: inset 0 1px 0 #ffffff;
        }*/
</style>
<script>

//    function submitpart()
//    {
//        if (jQuery("#partname").val() == "")
//        {
//            jQuery("#problem").show();
//            jQuery("#problem").fadeOut(3000);
//        } else
//        {
//            jQuery("#add_part").submit();
//        }
//    }
</script>
