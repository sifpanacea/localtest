<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Admin";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php

$page_nav["support admin management"]["sub"]["supportadmin"]["active"] = true;

include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Support Admin"] = "";
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
							<h2><?php echo lang('admin_dash_list_support_admin');?></h2>
		
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
					 <?php if ($support_admin): ?>
					<tr>
					    <th><?php echo lang('support_admin_first_name');?></th>
						<th><?php echo lang('support_admin_last_name');?></th>
						<th><?php echo lang('support_admin_username');?></th>
						<th><?php echo lang('support_admin_email');?></th>
						<th><?php echo lang('support_admin_mobile');?></th>
						<th><?php echo lang('support_admin_level');?></th>
						<th><?php echo lang('support_admin_status');?></th>
					</tr>
					<?php foreach ($support_admin as $admin=>$eachadmin):?>
					<tr>
                    <tbody>
					<tr>
					    <td><?php echo $eachadmin->first_name;?></td>
						<td><?php echo $eachadmin->last_name;?></td>
					    <td><?php echo $eachadmin->username;?></td>
						<td><?php echo $eachadmin->email;?></td>
						<td><?php echo $eachadmin->phone;?></td>
						<td><?php echo $eachadmin->level;?></td>
						<td><?php echo ($eachadmin->active) ? lang('support_admin_active'):lang('support_admin_inactive'); ?></td>
					</tr>
					<?php endforeach;?>
					<?php else: ?>
        			<p>
          				<?php echo lang('support_admin_empty');?>
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