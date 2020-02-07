<style>
    #search_table tbody tr td{
        cursor:default;
    }
</style>
<table id="search_table">
    <thead class="fixedHeader">
        <?php
        if ($_SESSION['customerno'] == 177) {
            ?>
            <tr>
                <th filter="false">Sr.No</th>
                <th></th>
                <?php
                if ($_SESSION['buzzer'] == 1) {
                    echo '<th></th>';
                }
                ?>
                <th  filter="false">Last Updated </th>
                <?php
                if ($_SESSION['groupid'] == 0) {
                    if (isset($_SESSION['group'])) {
                        echo "<th>" . $_SESSION['group'] . "</th>";
                    } else {
                        echo "<th>Group Name</th>";
                    }
                }
                ?>
                <th >Shop Name</th>
                <?php
                if ($_SESSION['Session_UserRole'] == 'elixir') {
                    echo "<th>Unit No</th>";
                }
                ?>
                <th filter="false">Temperature </th>

            </tr>
        <?php } else { ?>
            <tr>
                <th filter="false">Sr.No</th>
                <th></th>
                <?php
                if ($_SESSION['buzzer'] == 1) {
                    echo '<th></th>';
                }
                ?>
                <th  filter="false">Last Updated </th>
                <th filter="false">Power</th>
                <?php
                if ($_SESSION['groupid'] == 0) {
                    if (isset($_SESSION['group'])) {
                        echo "<th>" . $_SESSION['group'] . "</th>";
                    } else {
                        echo "<th>Group Name</th>";
                    }
                }if (isset($_SESSION["Warehouse"])) {
                    echo "<th>" . $_SESSION["Warehouse"] . "</th>";
                } else {
                    echo "<th>Warehouse</th>";
                }
                if ($_SESSION['Session_UserRole'] == 'elixir') {
                    echo "<th>Unit No</th>";
                }

                if ($_SESSION['customerno'] == speedConstants::CUSTNO_NXTDIGITAL) {
                    echo "<th>Location</th>";
                }
                if ($_SESSION['portable'] != '1') {
                    ?>
                    <?php
                    if ($_SESSION['use_loading'] == 1) {
                        echo '<th filter="false">Load</th>';
                    }
                    if ($_SESSION['use_ac_sensor'] == 1) {
                        echo "<th filter='false'>" . $_SESSION["digitalcon"] . "</th>";
                    }
                    if ($_SESSION['use_genset_sensor'] == 1) {
                        echo "<th filter='false'>" . $_SESSION["digitalcon"] . "</th>";
                    }
                   /* if ($_SESSION['use_door_sensor'] == 1) {
                        echo '<th filter="false">Door</th>  ';
                    }*/
                    if($_SESSION['customerno'] != 116){
                       if ($_SESSION['temp_sensors'] != 0) {
                            echo '<th filter="false">Temperature</th>';
                        } 
                    }
                    if ($_SESSION['use_extradigital'] == 1) {
                        echo '<th filter="false">' . $_SESSION['extradigitalstatus'] . '</th>';
                    }
                    //if ($_SESSION['use_warehouse'] == 1 && $_SESSION['use_tracking'] == 0 )
                       if($_SESSION['customerno'] == "2") {
                            echo '<th filter="false">Temp-Sensor Average</th>';
                    }
                    if ($_SESSION['use_humidity'] == 1) {
                        echo '<th filter="false">Humidity</th>';
                    }

                    if ($_SESSION['customerno'] == speedConstants::CUSTNO_MDLZ) {
                        echo '<th filter="false">Average Temp</th>';
                    }
                   // Follwing condition is added by Ranjeet on 19-03-2019 on local dev 
                    if ($_SESSION['use_door_sensor'] == 1) {
                        echo '<th filter="false">Door</th>';
                    }

                      
                }
                ?>
            </tr>
        <?php } ?>
    </thead>
    <tbody class="scrollContent">
