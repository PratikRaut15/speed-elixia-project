<script type="text/javascript">

function updateSqliteToDatabase() {
    var x = '0';
    var data={};
    data.analog1=[];
    data.analog2=[];
    data.analog3=[];
    data.analog4=[];
    data.hanalog1=[];
    data.hanalog2=[];
    data.hanalog3=[];
    data.hanalog4=[];
    
    $("input[name='analog1[]']").each(function() {
        var value = $(this).val();
        if (value) {
            x = '1';
            data.analog1.push(value)
            data.hanalog1.push($(this).next('input').val());
            
        }
    });
    $("input[name='analog2[]']").each(function() {
        var value = $(this).val();
        if (value) {
            x = '1';
            data.analog2.push(value)
            data.hanalog2.push($(this).next('input').val());
        }
    });

    $("input[name='analog3[]']").each(function() {
        var value = $(this).val();
        if (value) {
            x = '1';
            data.analog3.push(value)
            data.hanalog3.push($(this).next('input').val());
        }
    });

    $("input[name='analog4[]']").each(function() {
        var value = $(this).val();
        if (value) {
            x = '1';
            data.analog4.push(value)
            data.hanalog4.push($(this).next('input').val());
        }
    });
    if(x == '0'){
        alert("Please fill at least one field..."); 
        //$(selector).focus();
        return false;
    }else{
        data.act = 'updateSqliteToDatabase';
		jQuery('#pageloaddiv').show();
        jQuery.ajax({
        url:"route_ajax.php",
        cache: false,
        type: 'POST',
        dataType: "json",
        processData: true,
        data: JSON.stringify(data),
        success:function(result){
            jQuery('#pageloaddiv').hide();
            alert("Updated Successfully...!");
                setTimeout(function(){// wait for 5 secs(2)
                       location.reload(); // then reload the page.(3)
                  }, 100); 

            //
        },
        complete: function(){
            
        }
     });
    }

}

</script>
<?php
$title = ' Sqlite Report';

//echo table_header($title, $subTitle);
?>

<?php
$SDate = $STdate;
$STdate = date('d-m-Y', strtotime($STdate));
$t_columns = '';
$colspan = 0;

if($colspan>15){
    echo "<style>.newTable th, .newTable td{padding:3px;}</style>";
    echo '<div class="container-fluid" >'; //style="overflow:scroll;overflow-y:hidden;"
}
else{
    echo '<div class="container">';
}
?>

<form name="changeSqliteUpdate" id="changeSqliteUpdate" onsubmit="updateSqliteToDatabase();return false;">
 <table id='search_table_2' class="table newTable">
    <thead>
        <tr><th colspan="<?php echo $_SESSION['temp_sensors']*2+1; ?>"> Vehicle No. <?php echo "<b>".$vehicleno."</b>" ?></th></tr>
        <!-- <tr><td colspan="<?php echo $_SESSION['temp_sensors']*2+1; ?>" style="text-align: center; font-size: 14px; font-weight: bold;"> Double Click To Edit And "TAB" To Save The Changes</td></tr> -->
    <tr>
        <th>Last Updated</th>
        <?php
                if ($_SESSION['temp_sensors'] == 1) {
                    echo "<th>Temperature (&deg;C)</th>
                            <th>New Temperature (&deg;C)</th>
                    ";
                }
                if ($_SESSION['temp_sensors'] == 2) {
                    echo "<th>Temperature 1 (&deg;C)</th>
                          <th>New Temperature 1 (&deg;C)</th>
                          <th>Temperature 2 (&deg;C)</th>
                          <th>New Temperature 2 (&deg;C)</th>";
                }
                if ($_SESSION['temp_sensors'] == 3) {
                    echo "<th>Temperature 1 (&deg;C)</th>
                            <th>New Temperature 1 (&deg;C)</th>
                            <th>Temperature 2 (&deg;C)</th>
                            <th>New Temperature 2 (&deg;C)</th>    
                            ";
                    echo "<th>Temperature 3 (&deg;C)</th>
                    <th>New Temperature 3 (&deg;C)</th>";
                }
                if ($_SESSION['temp_sensors'] == 4) {

                    echo "<th>Temperature 1 (&deg;C)</th>
                    <th>New Temperature 1 (&deg;C)</th>
                    <th>Temperature 2 (&deg;C)</th>
                    <th>New Temperature 2 (&deg;C)</th>
                    ";
                    echo "<th>Temperature 3 (&deg;C)</th>
                    <th>New Temperature 3 (&deg;C)</th>
                    <th>Temperature 4 (&deg;C)</th>
                    <th>New Temperature 4 (&deg;C)</th>
                    ";
                    /*$t1 = getName_ByType($unit->n1);
                    if ($t1 == '') {
                        $t1 = 'Temperature 1';
                    }
                    $t2 = getName_ByType($unit->n2);
                    if ($t2 == '') {
                        $t2 = 'Temperature 1';
                    }
                    $t3 = getName_ByType($unit->n3);
                    if ($t3 == '') {
                        $t3 = 'Temperature 1';
                    }
                    $t4 = getName_ByType($unit->n4);
                    if ($t4 == '') {
                        $t4 = 'Temperature 1';
                    }
                    echo "<th>" . $t1 . " (&deg;C)</th><th>" . $t2 . " (&deg;C)</th>";
                    echo "<th>" . $t3 . " (&deg;C)</th><th>" . $t4 . " (&deg;C)</th>";*/
                }
                ?>  


        <!-- <th>Temperature 1</th>
        <th>Temperature 2</th>
        <th>Temperature 3</th>
        <th>Temperature 4</th> -->
    </tr>
    </thead>
