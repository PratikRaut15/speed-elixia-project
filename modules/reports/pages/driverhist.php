<form action="reports.php?id=2" method="POST">
<?php include 'panels/driverhist.php';?>
    <tr>
        <td>
            <select id="driverid" name="driverid">
            <option>Select Driver</option>
            <?php
            /*include 'getdrivers.php';
            foreach ($drivers as $driver) 
            {
                echo "<option value = '$driver->driverid'>$driver->drivername</option>";
            }*/
            ?>
            </select>
        </td>
        <td>Date</td>
        <td><?php $date = new DateTime();?>
            <input id="SDate" name="date" type="text" value="<?php echo $date->format('Y-m-d');?>" required/>
        </td>
        <td><!--<button id="trigger" class="g-button g-button-submit" >...</button>--></td>
        <td><input type="submit" value="Get Report" class="g-button g-button-submit" name="GetReport"></td>
    </tr>
    </tbody>
    </table>
</form>
<br>
<?php
/*if(isset($_POST['driverid']))
{
    if($_POST['driverid']!='Select Driver' && isset($_POST['date']))
    {
        include 'gettraveldata.php';
        if(isset($devices2))
        {
        }
    }
}*/
?>

<script>
Calendar.setup(
{
    inputField : "date", // ID of the input field
    ifFormat : "%Y-%m-%d", // the date format
    button : "trigger" // ID of the button
});
</script>