<style>
    #search_table tbody tr td {
        cursor: default;
    }
</style>
    <?php
        if ($_SESSION['customerno'] == 177) {
    ?>
            <table id="search_table">
                <thead class="fixedHeader">
                    <tr>
                        <th filter="false">SNo</th>
                        <th></th>
                        <th filter="false"></th>
                        <th filter="false"></th>
                        <?php
                        if ($_SESSION['buzzer'] == 1) {
                            echo '<th></th>';
                        }
                        ?>
                        <?php
                        if ($_SESSION['immobiliser'] == 1) {
                            echo '<th></th>';
                        }
                        ?>
                        <?php
                        if ($_SESSION['freeze'] == 1) {
                            echo '<th></th>';
                        }
                        ?>

                        <th filter="false">Last Updated </th>
                        <?php
                        if ($_SESSION['groupid'] == 0) {
                            ?>
                            <th>Group Name</th>
                        <?php
                    }
                    ?>
                        <?php if ($_SESSION['portable'] != '1') { ?>
                        <?php }
                    ?>
                        <th>Shop Name</th>
                        <?php
                        if ($_SESSION['Session_UserRole'] == 'elixir')
                            echo "<th>Unit No</th>";
                        ?>
                        <th>Location</th>
                        <?php
                        if ($_SESSION['portable'] != '1') {
                            ?>
                            <!--
                                            <th filter="false">Tamper</th>
                                            -->
                            <!--<th filter="false">Power Cut</th>-->
                            <th filter="false">Vigi Cooler</th>
                            <th filter="false">Veg Make Line</th>
                            <th filter="false">Non-Veg Make Line</th>
                            <th filter="false">Vertical Chiller</th>
                            <th filter="false">Deep Freezer Non-Veg</th>
                            <th filter="false">Deep Freezer Veg</th>
                            <th filter="false">Blast Freezer</th>
                            <th filter="false">Big Cold Room</th>
                            <th filter="false">Horizontal Chiller</th>
                            <th filter="false">Veg Chiller</th>
                        <?php
                    }
                    ?>
                        <th filter="false"></th>
                        <th filter="false"></th>
                    </tr>
                </thead>
                <tbody class="scrollContent">
        <?php } else {
        ?>
            <table id="search_table">
                <thead class="fixedHeader">
                    <tr>
                        <th filter="false">SNo</th>
                        <th></th>
                        <th filter="false"></th>
                        <th filter="false"></th>
                        <?php
                        if (isset($_SESSION['buzzer']) && $_SESSION['buzzer'] == 1) {
                            echo '<th></th>';
                        }
                        ?>
                        <?php
                        if (isset($_SESSION['immobiliser']) && $_SESSION['immobiliser'] == 1) {
                            echo '<th></th>';
                        }
                        ?>
                        <?php
                        if (isset($_SESSION['freeze']) && $_SESSION['freeze'] == 1) {
                            echo '<th></th>';
                        }
                        ?>
                        <th filter="false">Last Updated </th>
                        <?php
                        if (isset($_SESSION['ecodeid'])) {
                            ?>
                            <th>Group Name</th>
                        <?php } else {
                        if (isset($_SESSION['groupid']) && $_SESSION['groupid'] == 0) { ?>
                                <th>Group Name</th>
                            <?php }
                    }
                    ?>
                        <?php if (isset($_SESSION['portable']) && $_SESSION['portable'] != '1') { ?>
                            <th filter="false">Status</th>
                        <?php }
                    ?>
                        <?php
                        if (isset($_SESSION['customerno']) && $_SESSION['customerno'] == 212) {
                            if (isset($_SESSION['Warehouse'])) {
                                echo "<th >" . $_SESSION['Warehouse'] . "</th>";
                            } else {
                                echo "<th >Warehouse</th>";
                            }
                        } else {
                            echo "<th >Vehicle No</th>";
                        }
                        ?>
                        <th>Driver</th>
                        <?php
                        if (isset($_SESSION['Session_UserRole']) && $_SESSION['Session_UserRole'] == 'elixir') {
                            echo "<th>Unit No</th>";
                        }
                        ?>
                        <th>Location</th>
                        <th filter="false">Checkpoint</th>
                        <th filter="false">Route</th>
                        <?php 
                        /* Don not render following options if 'role_modal' in session is 'consignee'  */ 
                            if(isset($_SESSION['role_modal']) && strtolower($_SESSION['role_modal']) != 'consignee')
                            {
                                if (isset($_SESSION['portable']) && $_SESSION['portable'] != '1') {
                                    ?>
                                        <th filter="false">Speed (km/hr)</th>
                                        <?php
                                        if (isset($_SESSION['userid']) && $_SESSION['userid'] != 391 && $_SESSION['userid'] != 392) {
                                            ?>
                                            <th filter="false" class="tooltip-right" title="<?php echo DISTANCE_CONSTRAINT_NOTE; ?>">Distance (km)*</th>
                                        <?php
                                    }
                                    ?>
                                        <!--
                                                    <th filter="false">Tamper</th>
                                                    -->
                                        <!--<th filter="false">Power Cut</th>-->
                                        <?php
                                        //        if($_SESSION['use_fuel_sensor']==1){
                                        //           echo '<th filter="false">Fuel</th>';
                                        //      }
                                    
                                        if (isset($_SESSION['use_loading']) && $_SESSION['use_loading'] == 1) {
                                            echo '<th filter="false">Load</th>';
                                        }
                                        if ((isset($_SESSION['use_ac_sensor']) && $_SESSION['use_ac_sensor'] == 1) || (isset($_SESSION['use_genset_sensor']) && $_SESSION['use_genset_sensor'] == 1)) {
                                            echo "<th filter='false'>" . $_SESSION["digitalcon"] . "</th>";
                                        }
                                        /* if (isset($_SESSION['use_genset_sensor']) && $_SESSION['use_genset_sensor'] == 1) {
                                        echo "<th filter='false'>" . $_SESSION["digitalcon"] . "</th>";
                                    } */
                                        if (isset($_SESSION['use_door_sensor']) && $_SESSION['use_door_sensor'] == 1) {
                                            echo '<th filter="false">Door</th>  ';
                                        }
                                        if ($_SESSION['temp_sensors'] == 1) {
                                            echo '<th filter="false">Temperature</th>';
                                        } elseif ($_SESSION['temp_sensors'] == 2) {
                                            echo '<th filter="false">' . $_SESSION['Temperature 1'] . '</th>';
                                            echo '<th filter="false">' . $_SESSION['Temperature 2'] . '</th>';
                                        } elseif ($_SESSION['temp_sensors'] == 3) {
                                            echo '<th filter="false">' . $_SESSION['Temperature 1'] . '</th>';
                                            echo '<th filter="false">' . $_SESSION['Temperature 2'] . '</th>';
                                            echo '<th filter="false">' . $_SESSION['Temperature 3'] . '</th>';
                                        } elseif ($_SESSION['temp_sensors'] == 4) {
                                            echo '<th filter="false">' . $_SESSION['Temperature 1'] . '</th>';
                                            echo '<th filter="false">' . $_SESSION['Temperature 2'] . '</th>';
                                            echo '<th filter="false">' . $_SESSION['Temperature 3'] . '</th>';
                                            echo '<th filter="false">' . $_SESSION['Temperature 4'] . '</th>';
                                        }
                                        /* if ($_SESSION['use_extradigital'] == 1) {
                                        echo '<th filter="false">Genset 1</th>';
                                        echo '<th filter="false">Genset 2</th>';
                                    } */
        
                                        if ($_SESSION['use_extradigital'] == 1) {
                                            echo "<th filter='false'>Genset 2</th>";
                                        }
                                        if ($_SESSION['use_humidity'] == 1) {
                                            echo '<th filter="false">Humidity</th>';
                                        }
                                }
                            } 
                            else if(isset($_SESSION['role_modal']) && strtolower($_SESSION['role_modal']) == 'consignee')
                            {
                                if (isset($_SESSION['portable']) && $_SESSION['portable'] != '1') {
                                ?>    
                                    <th filter="false">Speed (km/hr)</th>
                                    <?php
                                        if (isset($_SESSION['userid']) && $_SESSION['userid'] != 391 && $_SESSION['userid'] != 392) {
                                            ?>
                                            <th filter="false" class="tooltip-right" title="<?php echo DISTANCE_CONSTRAINT_NOTE; ?>">Distance (km)*</th>
                                        <?php
                                    }
                                    
                                }
                            } elseif (isset($_SESSION['ecodeid']) && !isset($_SESSION['role_modal'])) {
                                if ($_SESSION['temp_sensors'] == 1) {
                                    echo '<th filter="false">Temperature</th>';
                                } elseif ($_SESSION['temp_sensors'] == 2) {
                                    echo '<th filter="false">' . $_SESSION['Temperature 1'] . '</th>';
                                    echo '<th filter="false">' . $_SESSION['Temperature 2'] . '</th>';
                                } elseif ($_SESSION['temp_sensors'] == 3) {
                                    echo '<th filter="false">' . $_SESSION['Temperature 1'] . '</th>';
                                    echo '<th filter="false">' . $_SESSION['Temperature 2'] . '</th>';
                                    echo '<th filter="false">' . $_SESSION['Temperature 3'] . '</th>';
                                } elseif ($_SESSION['temp_sensors'] == 4) {
                                    echo '<th filter="false">' . $_SESSION['Temperature 1'] . '</th>';
                                    echo '<th filter="false">' . $_SESSION['Temperature 2'] . '</th>';
                                    echo '<th filter="false">' . $_SESSION['Temperature 3'] . '</th>';
                                    echo '<th filter="false">' . $_SESSION['Temperature 4'] . '</th>';
                                }
                            }
                        ?>    
                         
                        
                        <th filter="false"></th>
                        <th filter="false"></th>
                    </tr>
                </thead>
                <tbody class="scrollContent">
                <?php } ?>