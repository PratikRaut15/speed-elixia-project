<style>
    #search_table tbody tr td{
        cursor:default;
    }
</style>

<table id="search_table">
    <thead class="fixedHeader">
    <tr>
        <th filter="false">SNo</th>
	
        <th  filter="false">Last Updated </th>
        <?php if($_SESSION['groupid'] == 0){ ?>
		<th>Group Name</th>
                <?php } ?>
        <?php if($_SESSION['portable']!='1') { ?>        
        <th filter="false">Status</th> 
        <?php } ?>
        <th >Vehicle No</th>
        
        <?php
        if($_SESSION['Session_UserRole']=='elixir')
            echo "<th>Unit No</th>";
        ?>
        <th >Location</th>
        
        <?php if($_SESSION['portable']!='1') { ?> 
        
        
        <th filter="false">Power Cut</th>
        <?php

        if($_SESSION['use_loading'] == 1){
            echo '<th filter="false">Load</th>';
        }
        if($_SESSION['use_ac_sensor'] == 1){
            echo "<th filter='false'>".$_SESSION["digitalcon"]."</th>";
        }
        if($_SESSION['use_genset_sensor'] == 1){
            echo "<th filter='false'>".$_SESSION["digitalcon"]."</th>";
        }
        if($_SESSION['use_door_sensor'] == 1)
        {
            echo '<th filter="false">Door</th>  ';
        }
        if($_SESSION['temp_sensors'] == 1)
        {
            echo '<th filter="false">Temperature</th>';
        }
        elseif($_SESSION['temp_sensors'] == 2)
        {
            echo '<th filter="false">Temperature 1</th>';
            echo '<th filter="false">Temperature 2</th>';
        }
        
        if($_SESSION['use_extradigital']==1){
            echo '<th filter="false">Status</th>';
        }
        }
        ?>
        <th filter="false"></th>                        
    </tr>
    </thead>
    <tbody class="scrollContent" >