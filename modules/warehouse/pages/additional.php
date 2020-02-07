<table id="search_table">
    <thead>
   <tr >
        <th>Last Updated</th>
        <th>Vehicle No</th>
        <?php
        if($_SESSION['Session_UserRole']=='elixir')
            echo "<th>Unit No</th>";
        ?>
        <th>Reason</th>
        <?php if($_SESSION['Session_UserRole']=='elixir') echo '<th>Data Status</th>';?>
        <?php
        if($_SESSION['Session_UserRole']=='elixir')
        {?>
        <th>Analog 3</th>
        <th>Analog 4</th>
        <?php }?>
        <?php if($_SESSION['Session_UserRole']=='elixir'){?>
        <th>CommandKey</th>
        <th>CommandKeyValue</th>
        <?php }?>
        <th>Phone No</th>
    </tr>
    </thead>
    <tbody >
    
