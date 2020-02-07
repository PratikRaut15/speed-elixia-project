<?php
    include_once 'nomenclature_functions.php';
    if (isset($_GET['delnid']) && $_GET['delnid']) {
    delNomens($_GET['delnid']);
}
    $nomensArray = getNomensList();
?>
<ul id="tabnav">
    <li><a href="nomenclature.php?id=1">Add Nomenclature</a></li> 
    <li><a class="selected" href="nomenclature.php?id=2">View Nomenclature</a></li> 
</ul>
<table class="table table-bordered table-striped dTableR dataTable" style=" width:70%;margin-top: 20px;">
    <thead>
        <tr>
            <th>Sr.No.</th>
            <th>Nomenclature </th>
            <th colspan="2">Actions</th>
        </tr>
    </thead>
    <?php if(isset($nomensArray) && !empty($nomensArray)){
        foreach($nomensArray as $nomensKey=>$nomensVal){ ?>  
            <tr>
                <td><?php echo ($nomensKey+1);?></td>
                <td><?php echo $nomensVal['name'];?></td>
                <td>
                    <a href="nomenclature.php?id=3&amp;nid=<?php echo $nomensVal['nid'];?>">
                    <i class="icon-pencil"></i>
                    </a>
                </td>
                <td>
                    <a href="nomenclature.php?id=2&amp;delnid=<?php echo $nomensVal['nid'];?>" onclick="return confirm('Are you sure you want to delete?');">
                    <i class="icon-trash"></i></a>
                </td>     
            </tr>
        <?php }
    }?>
</table>