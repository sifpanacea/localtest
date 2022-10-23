<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "New Customer Activation";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["customers management"]["sub"]["new_cust"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Cust. Management"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
     				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-0" data-widget-editbutton="false">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-user"></i> </span>
							<h2><?php echo lang('admin_dash_list_users');?></h2>
		
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
						<th><?php echo lang('customer_company_name');?></th>
						<th><?php echo lang('customer_company_address');?></th>
						<th><?php echo lang('customer_company_website');?></th>
						<th><?php echo lang('customer_company_contact_person');?></th>
						<th><?php echo lang('customer_company_contact_email');?></th>
						<th><?php echo lang('customer_company_contact_mobile');?></th>
						<th><?php echo lang('customer_plan');?></th>
						<th><?php echo lang('customer_plan_registered_on');?></th>
						<th><?php echo lang('customer_plan_expiry');?></th>
						<th><?php echo lang('customer_action_th');?></th>
					</tr>
					<?php foreach ($customerslist as $customers=>$eachcustomer):?>
                    <tbody>
					<tr>
						<td><?php echo $eachcustomer['display_company_name'];?></td>
						<td><?php echo $eachcustomer['company_address'];?></td>
						<td><?php echo $eachcustomer['company_website'];?></td>
						<td><?php echo $eachcustomer['contact_person'];?></td>
						<td><?php echo $eachcustomer['email'];?></td>
						<td><?php echo $eachcustomer['mobile_number'];?></td>
						<td><?php echo $eachcustomer['plan'];?></td>
						<td><?php echo $eachcustomer['registered_on'];?></td>
						<td><?php echo $eachcustomer['plan_expiry'];?></td>
						
						<td>
							<?php echo anchor("admin_dash/first_time_activate/". $eachcustomer['_id'], lang('customer_activate_link'));?>
                        </td>
					</tr>
					
					<?php endforeach;?>
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



<?php 
	//include footer
	include("inc/footer.php"); 
?>