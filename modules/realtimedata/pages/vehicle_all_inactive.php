<style>
/* tables */
table.tablesorter {
	font-family:arial;
	background-color: #CDCDCD;
	margin:10px 0pt 15px;
	font-size: 8pt;
	width: 100%;
	text-align: left;
}
table.tablesorter thead tr th, table.tablesorter tfoot tr th {
	background-color: #e6EEEE;
	border: 1px solid #FFF;
	font-size: 8pt;
	padding: 4px;
}
table.tablesorter thead tr .header {
	background-image: url(bg.gif);
	background-repeat: no-repeat;
	background-position: center right;
	cursor: pointer;
}
table.tablesorter tbody td {
	color: #3D3D3D;
	padding: 4px;
	background-color: #FFF;
	vertical-align: top;
}
table.tablesorter tbody tr.odd td {
	background-color:#F0F0F6;
}
table.tablesorter thead tr .headerSortUp {
	background-image: url(asc.gif);
}
table.tablesorter thead tr .headerSortDown {
	background-image: url(desc.gif);
}
table.tablesorter thead tr .headerSortDown, table.tablesorter thead tr .headerSortUp {
background-color: #8dbdd8;
}

</style>
<table id="myTable"  style="background-color:#FFFFFF; color:#000000; width:100%">
    <thead>
    <tr align="center">
        
		<tr>
         <!-- <th></th> -->
        <th>Sr. No.</th>        
        <th>Last Updated</th>
        <th>Vehicle No</th>
        <th>Customer no</th>
        <?php
      
            echo "<th>Unit No</th>";
        ?>
        
               
        <th>Phone No</th>
        <th>Network</th>
<!--        <th>Tamper</th>        -->
        <th>Power Cut</th>        
        <th>CRM Manager</th>
        <th>Issue Type</th>
        <th>Remark</th>
        
       <!-- <th>Temperature</th>    -->               
    </tr>
    </tr>
    </thead>
    <tbody  align="center">
    