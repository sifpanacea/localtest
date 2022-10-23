<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "API Users";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
if($usage == FALSE){
	$page_nav["thirdparty"]["sub"]["api_users"]["active"] = true;
}else{
	$page_nav["customers usage"]["sub"]["customer"]["active"] = true;
}
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
	$breadcrumbs["Third Party"] = "";
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
							<h2>API User(s)</h2>
		
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
					<?php if (!$usage): ?>
					 <?php if ($api_users): ?>
					<tr>
						<th>API Name</th>
						<th>Address</th>
						<th>Website</th>
						<th>Type</th>
						<th>Email</th>
						<th>Mobile</th>
						<th>Registered On</th>
						<th>User Name</th>
						<th>API Key</th>
						<th>API Accessy</th>
						<th>Status</th>
					</tr>
					<?php foreach ($api_users as $api=>$eachcustomer):?>
					<tr>
                    <tbody>
					<tr>
						<td><?php echo $eachcustomer['display_company_name'];?></td>
						<td><?php echo $eachcustomer['company_address'];?></td>
						<td><?php echo $eachcustomer['company_website'];?></td>
						<td><?php echo $eachcustomer['type'];?></td>
						<td><?php echo $eachcustomer['email'];?></td>
						<td><?php echo $eachcustomer['mobile_number'];?></td>
						<td><?php echo $eachcustomer['registered_on'];?></td>
						<td><?php echo $eachcustomer['username'];?></td>
						<td><?php echo $eachcustomer['api_key'];?></td>
						<td><?php echo $eachcustomer['access'];?></td>
						<td><?php if($eachcustomer['active'] == 1){echo "Active";}else{echo "Deactive";}?></td>
					</tr>
					<?php endforeach;?>
					<?php else: ?>
        			<p>
          				<?php echo "No APIs associated with your company yet.";?>
        			</p>
        			<?php endif ?>
        			<?php else: ?>
        			
        			
        			<?php if ($customerslist): ?>
					<tr>
						<th><?php echo lang('customer_company_name');?></th>
						<th><?php echo lang('customer_company_website');?></th>
						<th><?php echo lang('customer_company_contact_email');?></th>
						<th><?php echo lang('customer_company_contact_mobile');?></th>
						<th><?php echo lang('customer_action_th');?></th>
					</tr>
					<?php foreach ($customerslist as $customers=>$eachcustomer):?>
					<tr>
                    <tbody>
					<tr>
						<td><?php echo $eachcustomer['display_company_name'];?></td>
						<td><?php echo $eachcustomer['company_website'];?></td>
						<td><?php echo $eachcustomer['email'];?></td>
						<td><?php echo $eachcustomer['mobile_number'];?></td>
						<td><?php echo anchor("admin_dash/check_usage/".$eachcustomer['_id'], lang('customer_usage_link'))?></td>
					</tr>
					<?php endforeach;?>
					<?php else: ?>
        			<p>
          				<?php echo lang('customer_empty');?>
        			</p>
        			<?php endif ?>
        			
        			
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