<table>
    <tbody>
    <tr>
        <td>Last Updated</td>
        <td>Vehicle No</td>
        <td>Unit No</td>
        <td>Phone No</td>
        <td>Network Strength %</td>
        <?php if($_SESSION['Session_UserRole']=='elixir')
        echo '<td>GPS Available</td><td>GSM Register</td><td>GPRS Register</td>';
        ?>
    </tr>