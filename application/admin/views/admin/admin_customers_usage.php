<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Customers";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["customers usage"]["sub"]["customer"]["active"] = true;

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

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
     				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-group"></i> </span>
							<h2><?php echo langS('usage_of_company',array('%s' => $customerslist['display_company_name']));?></h2>
		
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							<div class="widget-body no-padding">
					<table id="dt_basic" class="table table-striped table-bordered table-hover">
					 <?php if ($customerslist): ?>
					<tr>
						<th><?php echo lang('customer_plan');?></th>
						<th><?php echo lang('usage_api');?></th>
						<th><?php echo lang('usage_app');?></th>
						<th><?php echo lang('usage_doc');?></th>
						<th><?php echo lang('usage_total_users');?></th>
						<th><?php echo lang('usage_on_wf');?></th>
						<th><?php echo lang('usage_off_wf');?></th>
						<th><?php echo lang('usage_disk');?></th>
					</tr>
					
					<tr>
                    <tbody>
					<tr>
						<td><?php echo $customerslist['plan'];?></td>
						<td><?php echo $count_array['api'];?></td>
						<td><?php echo $count_array['app'];?></td>
						<td><?php echo $count_array['doc'];?></td>
						<td><?php echo $count_array['users'];?></td>
						<td><?php echo $count_array['on_wf'];?></td>
						<td><?php echo $count_array['off_wf'];?></td>
						<td><?php echo $count_array['dbsize'];?></td>
						
					</tr>
					
					<?php else: ?>
        			<p>
          				<?php echo lang('customer_empty');?>
        			</p>
        			<?php endif ?>
        			
									</tbody>
								</table>
		
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div>
					<!-- end widget -->
					</article>
        
        </div><!-- ROW -->
				

	</div>
	<!-- END MAIN CONTENT -->

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
<script>
$(document).ready(function() {
	<?php if($message) { ?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
				content : "<?php echo $message?>",
				color : "#296191",
				iconSmall : "fa fa-bell bounce animated",
				timeout : 4000
			});
<?php } ?>
});
</script>


<?php 
	//include footer
	include("inc/footer.php"); 
?>