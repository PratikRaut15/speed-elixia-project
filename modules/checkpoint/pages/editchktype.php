
<?php
    $checkpoint = getchktype($_GET['ctid']);
?>
<form name="chktypecreate" id="chktypecreate" action="route.php" method="POST" style="widows: 80%;">
  <?php include 'panels/editchktype.php';?>
    <tr>
        <td>Name</td>
        <td><input type="text" name="typename" id="typename" value="<?php echo $checkpoint->name; ?>">
          <input type="hidden" name="ctid" id="ctid" value="<?php echo $checkpoint->ctid; ?>">
        </td>
    </tr>
  
  <tr>
    <td colspan="7" align="center">
      <input class="btn  btn-primary" type="button" name="modifychktype" id="modifychktype" value="Modify Checkpoint Type" onclick="editchekpointtypes();">&nbsp;
    </td>
  </tr>
</tbody>
</table>
</form>
<div id="map"></div>
