

    


<table id='search_table_2'>
  <thead>
    
    <tr>
	<td></td>
        <td>Vehicle</td>
        <?php 
        $devices = getroutes();
foreach ($devices as $device) 
{
    echo '<td>$device->routename</td>';
}
        ?>
        <td>End Time</td>
        <td>Start Location *</td>
        <td>End Location *</td>
        <td>Duration [Hours:Minutes]</td>
        <td>Distance [KM]</td>
		 <td>Cumulative Distance [KM]</td>
        <td>Status</td>
    </tr>
	</thead>
	<tbody>
    