<?php
if (isset($_SESSION['Warehouse'])) {
    $custom_wh = $_SESSION['Warehouse'];
} else {
    $custom_wh = "Warehouse";
}
if (isset($_SESSION['group'])) {
    $grp = $_SESSION['group'];
} else {
    $grp = "Group Name";
}
if ($_SESSION['customerno'] == speedConstants::CUSTNO_NXTDIGITAL) {
    ?>
    <script>

        jQuery(document).ready(function () {
            var warehouse ="<?php echo $custom_wh; ?>";
            var group ="<?php echo $grp; ?>";
            var grpid = "<?php echo $_SESSION['groupid']; ?>";
            if (grpid == '0') {
                jQuery('#search_table').tableFilter();
    <?php
    if ($_SESSION['Session_UserRole'] == 'elixir') {
        ?>
                    jQuery('#search_table_filter_3').attr('placeholder', 'Enter ' + group);
                    jQuery('#search_table_filter_4').attr('placeholder', 'Enter ' + warehouse);
                    jQuery('#search_table_filter_5').attr('placeholder', 'Enter Unit No');
                    jQuery('#search_table_filter_6').attr('placeholder', 'Enter Location');
        <?php
    } else {
        ?>
                    jQuery('#search_table_filter_3').attr('placeholder', 'Enter ' + group);
                    jQuery('#search_table_filter_4').attr('placeholder', 'Enter ' + warehouse);
                    jQuery('#search_table_filter_5').attr('placeholder', 'Enter Location');
        <?php
    }
    ?>
            }
            else {
                jQuery('#search_table').tableFilter();
    <?php
    if ($_SESSION['Session_UserRole'] == 'elixir') {
        ?>
                    jQuery('#search_table_filter_3').attr('placeholder', 'Enter ' + warehouse);
                    jQuery('#search_table_filter_4').attr('placeholder', 'Enter Unit No');
                    jQuery('#search_table_filter_5').attr('placeholder', 'Enter Location');
        <?php
    } else {
        ?>
                    jQuery('#search_table_filter_3').attr('placeholder', 'Enter ' + warehouse);
                    jQuery('#search_table_filter_4').attr('placeholder', 'Enter Location');
        <?php
    }
    ?>
            }
            close_map();
        });
    </script>
    <?php
} elseif ($_SESSION['customerno'] != 177) {
    ?>
    <script>
        jQuery(document).ready(function () {
            var warehouse ="<?php echo $custom_wh; ?>";
            var group ="<?php echo $grp; ?>";
            var grpid = "<?php echo $_SESSION['groupid']; ?>";
            if (grpid == '0') {
                jQuery('#search_table').tableFilter();
    <?php
    if ($_SESSION['Session_UserRole'] == 'elixir') {
        ?>
                    jQuery('#search_table_filter_4').attr('placeholder', 'Enter ' + group);
                    jQuery('#search_table_filter_5').attr('placeholder', 'Enter ' + warehouse);
                    jQuery('#search_table_filter_6').attr('placeholder', 'Enter Unit No');
        <?php
    } else {
        ?>
                    jQuery('#search_table_filter_4').attr('placeholder', 'Enter ' + group);
                    jQuery('#search_table_filter_5').attr('placeholder', 'Enter ' + warehouse);
        <?php
    }
    ?>
            }
            else {
                jQuery('#search_table').tableFilter();
    <?php
    if ($_SESSION['Session_UserRole'] == 'elixir') {
        ?>
                    jQuery('#search_table_filter_4').attr('placeholder', 'Enter ' + warehouse);
                    jQuery('#search_table_filter_5').attr('placeholder', 'Enter Unit No');
        <?php
    } else {
        ?>
                    jQuery('#search_table_filter_4').attr('placeholder', 'Enter ' + warehouse);
        <?php
    }
    ?>
            }
            close_map();
        });
    </script>
    <?php
} else {
    ?>
    <script>
        jQuery(document).ready(function () {
            var group ="<?php echo $grp; ?>";
            var grpid = "<?php echo $_SESSION['groupid']; ?>";
            if (grpid == '0') {
                jQuery('#search_table').tableFilter();
    <?php
    if ($_SESSION['Session_UserRole'] == 'elixir') {
        ?>
                    jQuery('#search_table_filter_4').attr('placeholder', 'Enter ' + group);
                    jQuery('#search_table_filter_5').attr('placeholder', 'Enter Shop Name');
                    jQuery('#search_table_filter_6').attr('placeholder', 'Enter Unit No');
        <?php
    } else {
        ?>
                    jQuery('#search_table_filter_4').attr('placeholder', 'Enter ' + group);
                    jQuery('#search_table_filter_5').attr('placeholder', 'Enter Shop Name');
        <?php
    }
    ?>
            }
            else {
                jQuery('#search_table').tableFilter();
    <?php
    if ($_SESSION['Session_UserRole'] == 'elixir') {
        ?>
                    jQuery('#search_table_filter_4').attr('placeholder', 'Enter Shop Name');
                    jQuery('#search_table_filter_5').attr('placeholder', 'Enter Unit No');
        <?php
    } else {
        ?>
                    jQuery('#search_table_filter_4').attr('placeholder', 'Enter Shop Name');
        <?php
    }
    ?>
            }
            close_map();
        });
    </script>
<?php } ?>