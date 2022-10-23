<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Saved Patterns result";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["home"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>

	
	<!-- MAIN CONTENT --><div id="content">
	<div class="well well-sm bg-color-purple txt-color-white text-center">
									
								</div>
								<div class="modal-dialog demo-modal">
									<div class="modal-content">
										<div class="modal-body">
										<br>
										<br>
										<div class="row no-space">
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											   <?php if ($result12): ?>
											   <?php $var = explode(",",$result12);?></td>
											   <?php $vv= array();?>
										     <?php foreach($var as $v):?>
											 <?php array_push($vv,$v);?>
											 <?php endforeach;?>
											 <?php $count = count($vv);?>
												<table class="table table-bordered">
									<?php $a = 0;?>
									<?php for($i=0;$i<$count;$i++):?>
									<tbody>
									
										<tr>
										    <td><?php echo array_shift($vv);?></td>

										</tr>
										
									</tbody>
									<?php endfor;?>
									<?php $a++;?>
					                
					                <?php else: ?>
        			                 <p>
          				           <?php echo lang('admin_no_saved_patterns');?>
        			                  </p>
        			               <?php endif ?>
								</table>
											</div>
										</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-right" onclick="window.history.back();">
											Back
										</button>
										</div>
									</div><!-- /.modal-content -->
								</div>
	<div class="well well-sm bg-color-purple txt-color-white text-center">
	
									
								</div>
	</div><!--END MAIN CONTENT-->

</div>
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->



<?php 
	//include footer
	include("inc/footer.php"); 
?>