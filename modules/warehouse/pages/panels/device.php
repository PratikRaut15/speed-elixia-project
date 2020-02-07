<table>
    <tbody>
    <tr>
        <td>Last Updated</td>
        <td>Vehicle No</td>
        <?php
        if($_SESSION['Session_UserRole']=='elixir')
            echo "<td>Unit No</td>";
        ?>
        <td>Network Strength%</td>
        <td>Int Batt(V)</td>
        <td>Tamper</td>
        <td>Power Cut</td>
        <td>Fuel Sensor</td>
        <td>Temperature</td>
        <td>Gen Set</td>
        <td>Phone No</td>
    </tr>