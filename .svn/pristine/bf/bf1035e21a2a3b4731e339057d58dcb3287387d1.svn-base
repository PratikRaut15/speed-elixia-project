<div class="scrollTable2">

<table id='search_table_2'>
    <thead>
    <tr>
        <td id="formheader" colspan="100%"><?php echo $_POST['report']." History From ".$_POST['STdate']; echo " To ".$_POST['EDdate'];?></td>
    </tr>
    <tr>
        <td colspan="100%">
            
            <?php 
            $ReportType = $_POST['report'];
            switch ($ReportType)
            {
                case 'Mileage':
                    echo 'Unit - KiloMeters';
                    break;
                case 'IdleTime':
                    echo 'Unit - Hours : Minutes';
                    break;
                case 'Genset':
                    echo 'Unit - Hours : Minutes';
                    break;
				 case 'Location':
                    echo 'Reported generated at 11:59 Pm ';
                    break;
                case 'Overspeed':
                case 'FenceConflict':
                    echo "Unit - No Of Times";
                    break;
                case 'Fuel':
                    echo "Fuel Consumed";
                    break;
            }
            ?>
        </td>
    </tr>
    <tr>
        <td>Vehicle No</td>
        <?php
        $SDate = $STdate;
        $STdate = date('d-m-Y', strtotime($STdate));
        while(strtotime($STdate)<=strtotime($EDdate))
        {
            echo "<td>".substr($STdate, 0,5)."</td>";
            $STdate = date("d-m-Y",strtotime('+1 day', strtotime($STdate)));
        }
        if($ReportType!="Location"){
        echo "<td>Total</td>";
        }
        ?>
    </tr>
    </thead>
