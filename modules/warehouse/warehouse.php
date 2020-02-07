<?php include '../panels/header.php';

?>
    <div class="entry">
	<center>

    	<?php include 'warehousetabs.php';?>
    </center>
	</div>
    <script type="text/javascript">
  var customerrefreshfrq =<?php echo $_SESSION['customerno']; ?>;
  var refr_int_time=<?php echo $_SESSION['refreshtime']; ?>
</script>
<?php include '../panels/footer.php';?>
