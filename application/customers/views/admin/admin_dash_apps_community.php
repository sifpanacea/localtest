<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = $category;

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["application"]["sub"]["communityapps"]["sub"][$category]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
        $breadcrumbs["Applications"]= "";
		$breadcrumbs["Community Apps"]= "";
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
							<h2><?php echo $category."&nbsp;Applications";?> <span class="badge bg-color-greenLight"><?php if(!empty($appcount)) {?><?php echo $appcount;?><?php } else {?><?php echo "0";?><?php }?></span></h2>
		
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
					<?php if ($applist): ?>
					<tr>
						<th><?php echo lang('index_app');?></th>
						<th><?php echo lang('index_app_properties');?></th>
						<th><?php echo lang('index_app_use');?></th>
					</tr>
					<?php $a = 0;?>
					<?php foreach ($applist as $app):?>
                    <tbody>
					<tr>
						<tr>
						<td><?php echo $app['app_name'];?></td>
						<td><?php echo anchor("dashboard/fetch_community_app_specification/".$app['app_id'], lang('app_properties'));?></td>
						<td><?php echo anchor("dashboard/get_community_app/".$app['app_id'].'/use', lang('app_use')) ;?></td>
					</tr>
					<?php $a++;?>
					<?php endforeach;?>
					<?php else: ?>
        			<p>
          				<?php echo lang('admin_no_apps');?>
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
	<?php if($message) {?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message",
				content : "<?php echo $message?>",
				color : "#296191",
				iconSmall : "fa fa-bell bounce animated",
				timeout : 1000
			});
<?php } ?>
});
</script>

<?php 
	//include footer
	include("inc/footer.php"); 
?>