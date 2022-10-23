<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "List Doctors";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["refer_doctors"]["sub"]["doctors"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Referral Doctors"] = "";
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
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							<h2>Doctors <span class="badge bg-color-greenLight"><?php if(!empty($drcount)) {?><?php echo $drcount;?><?php } else {?><?php echo "0";?><?php }?></span></h2>
		
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
					<?php $a = 0;?>
					<?php if ($doctors): ?>
					<tr>
						<th><?php echo lang('list_doctor_name');?></th>
						<th><?php echo lang('list_doctor_email');?></th>
						<th><?php echo lang('list_doctor_spec');?></th>
						<th colspan="3"><center><?php echo lang('list_doctor_action');?></center></th>
						
					</tr>
					<?php foreach ($doctors as $doctor):?>
                    <tbody>
					<tr>
						<td><?php echo $doctor['name'];?></td>
						<td><?php echo $doctor['email'];?></td>
						<td><?php echo $doctor['specialization'];?></td>
						<td><?php echo anchor("schoolhealth_sub_admin_portal/view_referral_doctor/".$doctor['_id'], lang('view_button'));?></td>
						<td><?php echo anchor("schoolhealth_sub_admin_portal/edit_referral_doctor/".$doctor['_id'].'/edit', lang('app_edit')) ;?></td>
						<td id="delete_doctor"><a class='ldelete' href='<?php echo URL."schoolhealth_sub_admin_portal/delete_referral_doctor/".$doctor['_id'];?>'>
                			<?php echo lang('app_delete')?>
                			</a>
					</tr>
					<?php $a++;?>
					<?php endforeach;?>
					<?php else: ?>
        			<p>
          				<?php echo lang('sub_admin_no_referral_dr_created');?>
        			</p>
        			<?php endif ?>
				</tbody>
				<?php if($links):?>
                <tfoot>
		        <tr>
				<td colspan="5">
                <?php echo $links; ?>
                </td>
				</tr>
				</tfoot>
				<?php endif ?>
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

<script>
	$(document).ready(function() {
	    
		<?php if($message) { ?>
	$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message",
				content : "<?php echo $message?>",
				color : "#296191",
				iconSmall : "fa fa-bell bounce animated",
				timeout : 4000
			});
	<?php } ?>
	
})

</script>
<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->



<?php 
	//include footer
	include("inc/footer.php"); 
?>