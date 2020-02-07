<?php include '../panels/header.php';?>

    <div class="entry">
	<div style="float:none; padding-left:22%;">
        <table id="floatingpanel">
            <thead>
            <tr>
                <th>Fleet Management</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Manage Vehicles (
                <a href="../vehicle/vehicle.php">Add</a> | 
                <a href="../vehicle/vehicle.php?id=2">View</a> )</td>
            </tr>  
            <tr>
                <td>Manage Drivers (
                <a href="../driver/driver.php">Add</a> | 
                <a href="../driver/driver.php?id=2">View</a> )</td>
            </tr>  
            <tr>
                <td>Manage Units (
                <a href="../unit/unit.php">View</a>) 
         		</td>
		    </tr>  
            <tr>
                <td>Manage Articles (
                <a href="../article/article.php">Add</a> | 
                <a href="../article/article.php?id=2">View</a> )</td>
            </tr>              
            </tbody>
        </table>
		</div>
		<div style="float:none;">
        <table id="floatingpanel">
            <thead>
            <tr>
                <th>Vehicle Mapping</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><a href="../vehicle/vehicle.php?id=3">Units To Vehicle </a></td>
            </tr>
            <tr>
                <td><a href="../driver/driver.php?id=3">Drivers To Vehicle </a></td>
            </tr>
            <tr>
                <td><a href="../article/article.php?id=3">Articles To Vehicle </a></td>
            </tr>            
            </tbody>
        </table>
		</div>
		<div style="float:none;">
        <table id="floatingpanel">
            <thead>
            <tr>
                <th>Account Management</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Manage Users (
                <a href="../account/users.php">Add</a> | 
                <a href="../account/users.php?id=2">View</a> )</td>                
            </tr>
            <tr>
                <td><a href="../user/accinfo.php">Modify Account / Change Password</a></td>
            </tr>            
            <tr>
                <td><a href="../user/accinfo.php?id=2">Contract Information</a></td>
            </tr>
            <tr>
                <td><a href="../user/accinfo.php?id=3">Alerts</a></td>
            </tr>
            </tbody>
        </table>
	</div>
    </div>
<?php include '../panels/footer.php';?>