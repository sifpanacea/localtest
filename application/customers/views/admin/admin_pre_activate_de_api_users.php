<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Activate (or) Deactivate API";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["thirdparty"]["sub"]["api_act_deact"]["active"] = true;
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
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-0" data-widget-editbutton="false">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-user"></i> </span>
							<h2>API user(s)</h2>
		
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
					<?php if ($api_users): ?>
					<tr>
						<th>API Name</th>
						<th>Type</th>
						<th>Email</th>
						<th>Mobile</th>
						<th>Registered On</th>
						<th>API Key</th>
						<th>API Accessy</th>
						<th>Action</th>
					</tr>
					<?php foreach ($api_users as $api=>$eachcustomer):?>
                    <tbody>
					<tr>
						<td><?php echo $eachcustomer['display_company_name'];?></td>
						<td><?php echo $eachcustomer['type'];?></td>
						<td><?php echo $eachcustomer['email'];?></td>
						<td><?php echo $eachcustomer['mobile_number'];?></td>
						<td><?php echo $eachcustomer['registered_on'];?></td>
						<td><?php echo $eachcustomer['api_key'];?></td>
						<td><?php echo $eachcustomer['access'];?></td>
						<td>
							<?php echo ($eachcustomer['active']) ? anchor("api/deactivate_api/".$eachcustomer['_id'], '<button class="btn btn-warning btn-xs">Deactivate</button>') : anchor("api/activate_api/". $eachcustomer['_id'], '<button class="btn btn-warning btn-xs">Activate</button>');?>
                        </td>
					</tr>
					<?php endforeach;?>
					<?php else: ?>
        			<p>
          				<?php echo "No APIs associated with your company yet.";?>
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