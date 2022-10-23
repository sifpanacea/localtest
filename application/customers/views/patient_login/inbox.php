<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "User Registration";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
//$page_css[] = "lockscreen.min.css";
$no_main_header = true;
include("inc/header.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">

	<!-- MAIN CONTENT -->
<div class="row" style="margin-top:150px">
<?php //echo $documents; ?>
<div class="col-md-6 col-md-offset-2 col-lg-6">
<div class="table-responsive">
<table class="table table-bordered">
<thead>
<tr>
<th>Application Name</th>
<th>Attach Files</th>
<th>options</th>
</tr>
</thead>
<tbody>
<?php foreach($documents as $document):?>
<tr>
<td><?php echo $document['app_name'];?></td>
<td><a href="" doc="<?php echo $document['doc_id'];?>" app="<?php echo $document['app_id'];?>">attach</a></td>
<td><a href="" doc="<?php echo $document['doc_id'];?>" app="<?php echo $document['app_id'];?>">view</a></td>
</tr>
<?php endforeach ?>

</tbody>
</table>
</div>
</div>
</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<!-- PAGE FOOTER -->
<?php
	// include page footer
	include("inc/footer.php");
?>
<!-- END PAGE FOOTER -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->

<script>

/* 	$(document).ready(function() {
		
		// PAGE RELATED SCRIPTS
		<?php if($message) { ?>
		$.smallBox({
						title     : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
						content   : "<?php echo $message?>",
						color     : "#2c699d",
						iconSmall : "fa fa-bell bounce animated",
						timeout   : 4000
						
					});
	<?php } ?>
	}) */

</script>
