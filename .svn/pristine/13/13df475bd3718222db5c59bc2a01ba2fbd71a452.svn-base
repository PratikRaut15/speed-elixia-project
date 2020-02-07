<?php
if(isset($_GET['nid'])) 
{
	$nomensArray = getNomensList($_GET['nid']);
	$nomensName = $nomensArray[0]['name'];
	  ?>
	  <ul id="tabnav">
	  	<li><a href="nomenclature.php?id=1">Add Nomenclature</a></li>
	  <li><a href="nomenclature.php?id=2">View Nomenclature</a></li>
	  <li><a href="nomenclature.php?id=3" class="selected">Edit Nomenclature</a></li>
	</ul>
	<form action="" style="width: 43%; margin-top: 20px;">
	  <div class="form-group">
	    <label for="email">Nomenclature Name:</label>
	    <input type="text" class="form-control" id="nomensName" value="<?php echo $nomensName; ?>">
	      <button type="button" class="btn btn-default" onclick="updateNomens(<?php echo $_GET['nid'];?>)">Save</button>
	  </div>
	</form>
	<?php 
}
?>