<?php
if ($_SESSION['customerno'] != 177) {
 ?>
 <script>
  jQuery(document).ready(function () {
    var grpid = "<?php echo $_SESSION['groupid']; ?>";
    if (grpid == '0') {
      jQuery('#search_table').tableFilter();
 <?php if ($_SESSION['Session_UserRole'] == 'elixir' && $_SESSION['buzzer'] == 1 && $_SESSION['immobiliser'] == 1) { ?>
       jQuery('#search_table_filter_8').attr('placeholder', 'Enter Group Name');
       jQuery('#search_table_filter_10').attr('placeholder', 'Enter Vehicle No');
       jQuery('#search_table_filter_11').attr('placeholder', 'Enter Driver Details');
       jQuery('#search_table_filter_12').attr('placeholder', 'Enter Unit No');
       jQuery('#search_table_filter_13').attr('placeholder', 'Enter Location');
 <?php } else if ($_SESSION['Session_UserRole'] == 'elixir' && ($_SESSION['buzzer'] == 1 || $_SESSION['immobiliser'] == 1)) { ?>
       jQuery('#search_table_filter_7').attr('placeholder', 'Enter Group Name');
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Vehicle No');
       jQuery('#search_table_filter_10').attr('placeholder', 'Enter Driver Details');
       jQuery('#search_table_filter_11').attr('placeholder', 'Enter Unit No');
       jQuery('#search_table_filter_12').attr('placeholder', 'Enter Location');
 <?php } else if ($_SESSION['Session_UserRole'] == 'elixir') { ?>
       jQuery('#search_table_filter_5').attr('placeholder', 'Enter Group Name');
       jQuery('#search_table_filter_7').attr('placeholder', 'Enter Vehicle No');
       jQuery('#search_table_filter_8').attr('placeholder', 'Enter Driver Details');
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Unit No');
       jQuery('#search_table_filter_10').attr('placeholder', 'Enter Location');
 <?php } else if ($_SESSION['buzzer'] == 1 && $_SESSION['immobiliser'] == 1) { ?>
       jQuery('#search_table_filter_8').attr('placeholder', 'Enter Group Name');
       jQuery('#search_table_filter_10').attr('placeholder', 'Enter Vehicle No');
       jQuery('#search_table_filter_11').attr('placeholder', 'Enter Driver Details');
       jQuery('#search_table_filter_12').attr('placeholder', 'Enter Location');
 <?php } else if ($_SESSION['buzzer'] == 1) { ?>
       jQuery('#search_table_filter_7').attr('placeholder', 'Enter Group Name');
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Vehicle No');
       jQuery('#search_table_filter_10').attr('placeholder', 'Enter Driver Details');
       jQuery('#search_table_filter_11').attr('placeholder', 'Enter Location');
 <?php } else if ($_SESSION['immobiliser'] == 1) { ?>
       jQuery('#search_table_filter_7').attr('placeholder', 'Enter Group Name');
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Vehicle No');
       jQuery('#search_table_filter_10').attr('placeholder', 'Enter Driver Details');
       jQuery('#search_table_filter_11').attr('placeholder', 'Enter Location');
 <?php } else if(isset($_SESSION['ecodeid'])){ ?>
       jQuery('#search_table_filter_5').attr('placeholder', 'Enter Group Name');
       jQuery('#search_table_filter_7').attr('placeholder', 'Enter Vehicle No');
       jQuery('#search_table_filter_8').attr('placeholder', 'Enter Driver Details');
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Location');
 <?php 
   }else { ?>
       jQuery('#search_table_filter_6').attr('placeholder', 'Enter Group Name');
       jQuery('#search_table_filter_8').attr('placeholder', 'Enter Vehicle No');
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Driver Details');
       jQuery('#search_table_filter_10').attr('placeholder', 'Enter Location');
 <?php } ?>
    } else {
      jQuery('#search_table').tableFilter();
 <?php 
 if ($_SESSION['Session_UserRole'] == 'elixir' && $_SESSION['buzzer'] == 1 && $_SESSION['immobiliser'] == 1) { ?>
       jQuery('#search_table_filter_10').attr('placeholder', 'Enter Vehicle No');
       jQuery('#search_table_filter_11').attr('placeholder', 'Enter Driver Details');
       jQuery('#search_table_filter_12').attr('placeholder', 'Enter Unit No');
       jQuery('#search_table_filter_13').attr('placeholder', 'Enter Location');
 <?php } else if ($_SESSION['Session_UserRole'] == 'elixir' && ($_SESSION['buzzer'] == 1 || $_SESSION['immobiliser'] == 1)) { ?>
       jQuery('#search_table_filter_8').attr('placeholder', 'Enter Vehicle No');
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Driver Details');
       jQuery('#search_table_filter_10').attr('placeholder', 'Enter Unit No');
       jQuery('#search_table_filter_11').attr('placeholder', 'Enter Location');
 <?php } else if ($_SESSION['Session_UserRole'] == 'elixir') { ?>
       jQuery('#search_table_filter_7').attr('placeholder', 'Enter Vehicle No');
       jQuery('#search_table_filter_8').attr('placeholder', 'Enter Driver Details');
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Unit No');
       jQuery('#search_table_filter_10').attr('placeholder', 'Enter Location');
 <?php } else if ($_SESSION['buzzer'] == 1 && $_SESSION['immobiliser'] == 1) { ?>
       jQuery('#search_table_filter_10').attr('placeholder', 'Enter Vehicle No');
       jQuery('#search_table_filter_11').attr('placeholder', 'Enter Driver Details');
       jQuery('#search_table_filter_12').attr('placeholder', 'Enter Location');
 <?php } else if ($_SESSION['buzzer'] == 1) { ?>
       jQuery('#search_table_filter_8').attr('placeholder', 'Enter Vehicle No');
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Driver Details');
       jQuery('#search_table_filter_10').attr('placeholder', 'Enter Location');
 <?php } else if ($_SESSION['immobiliser'] == 1) { ?>
       jQuery('#search_table_filter_8').attr('placeholder', 'Enter Vehicle No');
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Driver Details');
       jQuery('#search_table_filter_10').attr('placeholder', 'Enter Location');
 <?php }  else if(isset($_SESSION['ecodeid'])){ ?>
       jQuery('#search_table_filter_5').attr('placeholder', 'Enter Group Name');
       jQuery('#search_table_filter_7').attr('placeholder', 'Enter Vehicle No');
       jQuery('#search_table_filter_8').attr('placeholder', 'Enter Driver Details');
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Location');
 <?php 
   }else { ?>
       jQuery('#search_table_filter_5').attr('placeholder', 'Enter Group Name');
       jQuery('#search_table_filter_7').attr('placeholder', 'Enter Vehicle No');
       jQuery('#search_table_filter_8').attr('placeholder', 'Enter Driver Details');
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Location');
 <?php } ?>
    }
    close_map();
  });
 </script>
<?php } else { ?>
 <script>
  jQuery(document).ready(function () {
    var grpid = "<?php echo $_SESSION['groupid']; ?>";
    if (grpid == '0') {

      jQuery('#search_table').tableFilter();
 <?php
 if ($_SESSION['Session_UserRole'] == 'elixir' && $_SESSION['buzzer'] == 1 && $_SESSION['immobiliser'] == 1) {
  ?>
       jQuery('#search_table_filter_8').attr('placeholder', 'Enter Group Name');
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Shop Name');
       jQuery('#search_table_filter_10').attr('placeholder', 'Enter Unit No');
       jQuery('#search_table_filter_11').attr('placeholder', 'Enter Location');
  <?php
 } else if ($_SESSION['Session_UserRole'] == 'elixir' && ($_SESSION['buzzer'] == 1 || $_SESSION['immobiliser'] == 1)) {
  ?>
       jQuery('#search_table_filter_7').attr('placeholder', 'Enter Group Name');
       jQuery('#search_table_filter_8').attr('placeholder', 'Enter Shop Name');
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Unit No');
       jQuery('#search_table_filter_10').attr('placeholder', 'Enter Location');
  <?php
 } else if ($_SESSION['Session_UserRole'] == 'elixir') {
  ?>
       jQuery('#search_table_filter_6').attr('placeholder', 'Enter Group Name');
       jQuery('#search_table_filter_7').attr('placeholder', 'Enter Shop Name');
       jQuery('#search_table_filter_8').attr('placeholder', 'Enter Unit No');
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Location');
  <?php
 } else if ($_SESSION['buzzer'] == 1 && $_SESSION['immobiliser'] == 1) {
  ?>
       jQuery('#search_table_filter_8').attr('placeholder', 'Enter Group Name');
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Shop Name');
       jQuery('#search_table_filter_11').attr('placeholder', 'Enter Location');
  <?php
 } else if ($_SESSION['buzzer'] == 1) {
  ?>
       jQuery('#search_table_filter_7').attr('placeholder', 'Enter Group Name');
       jQuery('#search_table_filter_8').attr('placeholder', 'Enter Shop Name');
       jQuery('#search_table_filter_10').attr('placeholder', 'Enter Location');
  <?php
 } else if ($_SESSION['immobiliser'] == 1) {
  ?>
       jQuery('#search_table_filter_7').attr('placeholder', 'Enter Group Name');
       jQuery('#search_table_filter_8').attr('placeholder', 'Enter Shop Name');
       jQuery('#search_table_filter_10').attr('placeholder', 'Enter Location');
  <?php
 } else {
  ?>
       jQuery('#search_table_filter_6').attr('placeholder', 'Enter Group Name');
       jQuery('#search_table_filter_7').attr('placeholder', 'Enter Shop Name');
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Location');
  <?php
 }
 ?>
    }
    else {
      jQuery('#search_table').tableFilter();
 <?php
 if ($_SESSION['Session_UserRole'] == 'elixir' && $_SESSION['buzzer'] == 1 && $_SESSION['immobiliser'] == 1) {
  ?>
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Shop Name');
       jQuery('#search_table_filter_11').attr('placeholder', 'Enter Unit No');
       jQuery('#search_table_filter_12').attr('placeholder', 'Enter Location');
  <?php
 } else if ($_SESSION['Session_UserRole'] == 'elixir' && ($_SESSION['buzzer'] == 1 || $_SESSION['immobiliser'] == 1)) {
  ?>
       jQuery('#search_table_filter_7').attr('placeholder', 'Enter Shop Name');
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Unit No');
       jQuery('#search_table_filter_10').attr('placeholder', 'Enter Location');
  <?php
 } else if ($_SESSION['Session_UserRole'] == 'elixir') {
  ?>
       jQuery('#search_table_filter_6').attr('placeholder', 'Enter Shop Name');
       jQuery('#search_table_filter_8').attr('placeholder', 'Enter Unit No');
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Location');
  <?php
 } else if ($_SESSION['buzzer'] == 1 && $_SESSION['immobiliser'] == 1) {
  ?>
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Shop Name');
       jQuery('#search_table_filter_11').attr('placeholder', 'Enter Location');
  <?php
 } else if ($_SESSION['buzzer'] == 1) {
  ?>
       jQuery('#search_table_filter_7').attr('placeholder', 'Enter Vehicle No');
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Location');
  <?php
 } else if ($_SESSION['immobiliser'] == 1) {
  ?>
       jQuery('#search_table_filter_7').attr('placeholder', 'Enter Vehicle No');
       jQuery('#search_table_filter_9').attr('placeholder', 'Enter Location');
  <?php
 } else {
  ?>
       jQuery('#search_table_filter_6').attr('placeholder', 'Enter Vehicle No');
       jQuery('#search_table_filter_8').attr('placeholder', 'Enter Location');
  <?php
 }
 ?>
    }
    close_map();
  });
 </script>
<?php } ?>