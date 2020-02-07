<table id="search_table">
    <thead>
    <tr>
        <th>Last Updated</th>
        <th>Vehicle No</th>
        <th>Unit No</th>
        <th>Phone No</th>
        <th>Network Strength %</th>
        <?php if($_SESSION['Session_UserRole']=='elixir')
        echo '<th>GPS Available</th><th>GSM Register</th><th>GPRS Register</th>';
        ?>
    </tr>
    </thead>
    <tbody>
    