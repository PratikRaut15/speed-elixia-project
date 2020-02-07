<?php include 'panels/mapvehicles.php';?>
    <tr>
        <td width="150" class="assign"><?php printunitsformapping();?></td>
        <td width="50">
            <input type="button" value="Map" id="mapper" class="btn  btn-primary" onclick="mapselection();">
            <br><br>
            <input type="button" value="Demap" class="btn  btn-primary" onclick="demap();">
        </td>
        <td width="150" class="assign"><?php printvehiclesformapping();?></td>
    </tr>
    </tbody>
</table>