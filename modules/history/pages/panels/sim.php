<div class="scrollTable">
<table>
<thead>
    <tr>
        <th colspan="100%" id="formheader">Sim Data - <?php echo tableheader();?></th>
    </tr>
    <tr>
        <td>Date Time</td>
        <td>Network Strength %</td>
        <?php if($_SESSION['Session_UserRole']=='elixir')
        echo '<td>GPS Available</td><td>GSM Register</td><td>GPRS Register</td>';
        ?>
    </tr>
</thead>
<tbody>
