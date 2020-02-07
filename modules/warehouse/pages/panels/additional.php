<table>
    <tbody>
    <tr>
        <td>Last Updated</td>
        <td>Vehicle No</td>
        <?php
        if($_SESSION['Session_UserRole']=='elixir')
            echo "<td>Unit No</td>";
        ?>
        <td>Reason</td>
        <?php if($_SESSION['Session_UserRole']=='elixir') echo '<td>Data Status</td>';?>
        <?php
        if($_SESSION['Session_UserRole']=='elixir')
        {?>
        <td>Analog 3</td>
        <td>Analog 4</td>
        <?php }?>
        <?php if($_SESSION['Session_UserRole']=='elixir'){?>
        <td>CommandKey</td>
        <td>CommandKeyValue</td>
        <?php }?>
        <td>Phone No</td>
    </tr>
