


<?php
$pg = isset($_GET['pg']) ? $_GET['pg'] : '';
$catid = isset($_GET['catid']) ? $_GET['catid'] : '';
$stateid = isset($_GET['stateid']) ? $_GET['stateid'] : '';
$styleid = isset($_GET['styleid']) ? $_GET['styleid'] : '';
$asmid = isset($_GET['asmid']) ? $_GET['asmid'] : '';
$areaid = isset($_GET['areaid']) ? $_GET['areaid'] : '';
$distid = isset($_GET['distid']) ? $_GET['distid'] : '';
$saleid = isset($_GET['saleid']) ? $_GET['saleid'] : '';
$sid = isset($_GET['sid']) ? $_GET['sid'] : '';
$oid = isset($_GET['orderid']) ? $_GET['orderid'] : '';
$prid = isset($_GET['prid']) ? $_GET['prid'] : '';
$stypeid = isset($_GET['stypeid']) ? $_GET['stypeid'] : '';
$stockid = isset($_GET['stockid']) ? $_GET['stockid'] : '';
$orderid = isset($_GET['orderid']) ? $_GET['orderid'] : '';
$invid = isset($_GET['invid']) ? $_GET['invid'] : '';   //inventory id
$atid = isset($_GET['atid']) ? $_GET['atid'] : '';   //attendance id
if ($pg == 'map' || $pg == 'allorder' || $pg == 'allprimarysales' || $pg == 'dashboard' || $pg == 'inventoryedit' || $pg == 'inventoryview' || $pg == 'inventoryadd') {
    $disp = "none";
} else {
    $disp = "block";
}
?>

<ul id="tabnav" style='display:<?php echo $disp; ?>;'>
    <?php
    if (($pg == "stypeadd") || ($pg == "stypeview") || ($pg == "stypeedit")) {
        ?>
        <li><a class='<?php
            if ($pg == 'stypeadd') {
                echo "selected";
            }
            ?>' href='sales.php?pg=stypeadd'>Add Shop type</a></li>
        <li><a class='<?php
        if ($pg == 'stypeview') {
            echo "selected";
        }
        ?>' href='sales.php?pg=stypeview'>View Shop type</a></li>
            <?php if ($stypeid != "") { ?> <li><a class='<?php
                if ($pg == 'stypeedit') {
                    echo "selected";
                }
                ?>' href='sales.php?pg=stypeedit&cid=<?php echo $stypeid; ?>'>Edit Shop type</a></li>
                <?php
            }
        } else if (($pg == "category") || ($pg == "catview") || ($pg == "catedit")) {
            ?>
        <li><a class='<?php
            if ($pg == 'category') {
                echo "selected";
            }
            ?>' href='sales.php?pg=category'>Add Category</a></li>
        <li><a class='<?php
            if ($pg == 'catview') {
                echo "selected";
            }
            ?>' href='sales.php?pg=catview'>View Category</a></li>
            <?php if ($catid != "") { ?>
            <li><a class='<?php
                if ($pg == 'catedit') {
                    echo "selected";
                }
                ?>' href='sales.php?pg=catedit&cid=<?php echo $catid; ?>'>Edit Category</a></li>
                <?php
            }
        } else if (($pg == "state") || ($pg == "stateview") || ($pg == "stateedit")) {
            ?>
        <li><a class='<?php
            if ($pg == 'state') {
                echo "selected";
            }
            ?>' href='sales.php?pg=state'>Add State</a></li>
        <li><a class='<?php
            if ($pg == 'stateview') {
                echo "selected";
            }
            ?>' href='sales.php?pg=stateview'>View State</a></li>
            <?php if ($stateid != "") { ?>
            <li><a class='<?php
            if ($pg == 'stateedit') {
                echo "selected";
            }
            ?>' href='sales.php?pg=stateedit&stateid=<?php echo $stateid; ?>'>Edit State</a></li>
            <?php } ?>

            <?php
           } else if (($pg == "style") || ($pg == "styleview") || ($pg == "styleedit")) {
               ?>
        <li><a class='<?php
            if ($pg == 'style') {
                echo "selected";
            }
            ?>' href='sales.php?pg=style'>Add SKU</a></li>
        <li><a class='<?php
            if ($pg == 'styleview') {
                echo "selected";
            }
            ?>' href='sales.php?pg=styleview'>View SKU</a></li>
            <?php if ($styleid != "") { ?>
            <li><a class='<?php
                if ($pg == 'styleedit') {
                    echo "selected";
                }
                ?>' href='sales.php?pg=styleedit&styleid=<?php echo $styleid; ?>'>Edit SKU</a></li>
                <?php
            }
        } else if (($pg == "asm") || ($pg == "asmview") || ($pg == "asmedit")) {
            ?>
        <li><a class='<?php
            if ($pg == 'asm') {
                echo "selected";
            }
            ?>' href='sales.php?pg=asm'>Add ASM</a></li>
        <li><a class='<?php
            if ($pg == 'asmview') {
                echo "selected";
            }
            ?>' href='sales.php?pg=asmview'>View ASM</a></li>
            <?php if ($asmid != "") { ?>
            <li><a class='<?php
                if ($pg == 'asmedit') {
                    echo "selected";
                }
                ?>' href='sales.php?pg=asmedit&asmid=<?php echo $asmid; ?>'>Edit ASM</a></li>
                <?php
            }
        } else if (($pg == "area") || ($pg == "areaview") || ($pg == "areaedit")) {
            ?>
        <li><a class='<?php
            if ($pg == 'area') {
                echo "selected";
            }
            ?>' href='sales.php?pg=area'>Add Area</a></li>
        <li><a class='<?php
            if ($pg == 'areaview') {
                echo "selected";
            }
            ?>' href='sales.php?pg=areaview'>View Area</a></li>
            <?php if ($areaid != "") { ?>
            <li><a class='<?php
                if ($pg == 'areaedit') {
                    echo "selected";
                }
                ?>' href='sales.php?pg=areaedit&areaid=<?php echo $areaid; ?>'>Edit Area</a></li>
                <?php
            }
        } else if (($pg == "dist") || ($pg == "distview") || ($pg == "distedit")) {
            ?>
        <li><a class='<?php
            if ($pg == 'dist') {
                echo "selected";
            }
            ?>' href='sales.php?pg=dist'>Add Distributor</a></li>
        <li><a class='<?php
            if ($pg == 'distview') {
                echo "selected";
            }
            ?>' href='sales.php?pg=distview'>View Distributor</a></li>
            <?php if ($distid != "") { ?>
            <li><a class='<?php
                if ($pg == 'distedit') {
                    echo "selected";
                }
                ?>' href='sales.php?pg=distedit&distid=<?php echo $distid; ?>'>Edit Distributor</a></li>
                <?php
            }
        } else if (($pg == "sales") || ($pg == "saleview") || ($pg == "saleedit")) {
            ?>
        <li><a class='<?php
            if ($pg == 'sales') {
                echo "selected";
            }
            ?>' href='sales.php?pg=sales'>Add Sales</a></li>
        <li><a class='<?php
            if ($pg == 'saleview') {
                echo "selected";
            }
            ?>' href='sales.php?pg=saleview'>View Sales</a></li>
            <?php if ($saleid != "") { ?>
            <li><a class='<?php
                if ($pg == 'saleedit') {
                    echo "selected";
                }
                ?>' href='sales.php?pg=saleedit&saleid=<?php echo $distid; ?>'>Edit Sales</a></li>
            <?php } ?>
            <?php
            if ($pg == 'edit') {
                echo "<li><a class='selected' href='sales.php?pg=sales'>Add Sales</a></li>";
            }
        } else if (($pg == "shop") || ($pg == "shopview") || ($pg == "shopedit")) {
            ?>
        <li><a class='<?php
            if ($pg == 'shop') {
                echo "selected";
            }
            ?>' href='sales.php?pg=shop'>Add Shop</a></li>
        <li><a class='<?php
            if ($pg == 'shopview') {
                echo "selected";
            }
            ?>' href='sales.php?pg=shopview'>View Shop</a></li>
            <?php if ($sid != "") { ?>
            <li><a class='<?php
                if ($pg == 'shopedit') {
                    echo "selected";
                }
                ?>' href='sales.php?pg=shopedit&sid=<?php echo $sid; ?>'>Edit Shop</a></li>
                   <?php
               }
           } else if (($pg == "entry") || ($pg == "entryview")) {
               ?>
        <li><a class='<?php
            if ($pg == 'entry') {
                echo "selected";
            }
            ?>' href='sales.php?pg=entry'>Add Entry</a></li>
        <li><a class='<?php
            if ($pg == 'entryview') {
                echo "selected";
            }
            ?>' href='sales.php?pg=entryview'>Entry View</a></li>
            <?php
           } else if (($pg == "order") || ($pg == "orderview") || ($pg == "orderedit") || ($pg=='orderonhold')) {
               ?>
        <li><a class='<?php
            if ($pg == 'order') {
                echo "selected";
            }
            ?>' href='sales.php?pg=order'>Add Order</a></li>
        <li><a class='<?php
               if ($pg == 'orderview') {
                   echo "selected";
               }
               ?>' href='sales.php?pg=orderview'>View Orders</a></li>
             <li><a class='<?php
               if ($pg == 'orderonhold') {
                   echo "selected";
               }
               ?>' href='sales.php?pg=orderonhold   '>On Hold Orders</a></li>
            <?php if ($oid != "") { ?> <li><a class='<?php
                   if ($pg == 'orderedit') {
                       echo "selected";
                   }
                   ?>' href='sales.php?pg=orderedit&orderid=<?php echo $orderid; ?>'>Edit Order</a></li><?php } ?>
            <?php
        } else if (($pg == "stock") || ($pg == "stockview") || ($pg == "stockedit")) {
            ?>
        <li><a class='<?php
            if ($pg == 'stock') {
                echo "selected";
            }
            ?>' href='sales.php?pg=stock'>Add Dead Stock</a></li>
        <li><a class='<?php
            if ($pg == 'stockview') {
                echo "selected";
            }
            ?>' href='sales.php?pg=stockview'>Dead Stock View</a></li>
            <?php if ($stockid != "") { ?>
            <li><a class='<?php
                if ($pg == 'stockedit') {
                    echo "selected";
                }
                ?>' href='sales.php?pg=stockedit&stockid=<?php echo $stockid; ?>'>Edit Stock</a></li>
                <?php
            }
        } else if (($pg == "prisales") || ($pg == "prisalesview") || ($pg == "editprisales")) {
            ?>
        <li><a class='<?php
            if ($pg == 'prisales') {
                echo "selected";
            }
            ?>' href='sales.php?pg=prisales'>Add Primary Sales</a></li>
        <li><a class='<?php
            if ($pg == 'prisalesview') {
                echo "selected";
            }
            ?>' href='sales.php?pg=prisalesview'>Primary Sales View</a></li>
            <?php if ($prid != "") { ?>
            <li><a class='<?php
                if ($pg == 'editprisales') {
                    echo "selected";
                }
                ?>' href='sales.php?pg=editprisales&prid=<?php echo $prid; ?>'>Edit Primary Sales</a></li>
                <?php
            }
        } else if (($pg == "inventoryedit") || ($pg == "inventoryview") || ($pg == "inventoryadd")) {
            ?>
        <li><a class='<?php
            if ($pg == 'inventoryadd') {
                echo "selected";
            }
            ?>' href='sales.php?pg=inventoryadd'>Add Inventory</a></li>
        <li><a class='<?php
            if ($pg == 'inventoryview') {
                echo "selected";
            }
            ?>' href='sales.php?pg=inventoryview'>View Inventory</a></li>
            <?php if ($invid != "") { ?>
            <li><a class='<?php
                if ($pg == 'inventoryedit') {
                    echo "selected";
                }
                ?>' href='sales.php?pg=inventoryedit&invid=<?php echo $invid; ?>'>Edit Inventory</a></li>
                <?php
            }
        } else if (($pg == "attendanceedit") || ($pg == "attendanceview") || ($pg == "attendanceadd")) {
            ?>
        <li><a class='<?php
            if ($pg == 'attendanceadd'){
                echo "selected";
            }
            ?>' href='sales.php?pg=attendanceadd'>Add Attendance</a></li>
        <li><a class='<?php
            if ($pg == 'attendanceview') {
                echo "selected";
            }
            ?>' href='sales.php?pg=attendanceview'>View Attendance</a></li>
    <?php if ($atid != ""){ ?>
        <li><a class='<?php
        if($pg == 'attendanceedit'){
            echo "selected";
        }
        ?>' href='sales.php?pg=attendanceedit&atid=<?php echo $atid; ?>'>Edit Attendance</a></li>
        <?php
    }
}
?>
</ul>


