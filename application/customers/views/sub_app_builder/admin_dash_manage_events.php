<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Manage Events";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["events"]["sub"]["manageeventapp"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Events"] = "";
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
							<h2><?php echo lang('admin_dash_manage_events');?></h2>
		
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
					<?php $u = 0;?>
					<?php if ($events): ?>
					<tr>
						<th><?php echo lang('index_event_name');?></th>
						<th><?php echo lang('index_event_desc');?></th>
						<th><?php echo lang('index_event_requested_user');?></th>
						<th><?php echo lang('index_event_requested_time');?></th>
						<th><?php echo lang('index_event_attachment');?></th>
						<th><?php echo lang('index_event_comments');?></th>
						<th><?php echo lang('index_event_edit');?></th>
					</tr>
					<?php foreach ($events as $event):?>
                    <tbody>
					<tr>
						<td><?php echo $event['event_name'];?></td>
						<td><?php echo $event['event_desc'];?></td>
						<td><?php echo $event['requested_user_id'];?></td>
						<td><?php echo $event['req_time'];?></td>
						<?php if(isset($event['attachment']) && !empty($event['attachment'])):?><?php $pathvar = str_replace('/','=',$event['attachment']['file_path']); ?><?php endif ?>
						<?php $evname  = base64_encode($event['event_name']);?>
                        <td><?php if(isset($event['attachment']) && !empty($event['attachment'])):?><a target='_blank' href='file_download/<?php echo $pathvar;?>' name='<?php echo $event['attachment']['file_client_name']?>'>Download file (<?php echo $event['attachment']['file_client_name'];?>)</a><?php else: ?><?php echo "No files attached";?><?php endif ?></td>
						<td><?php echo $event['comments'];?></td>
						<td><?php echo anchor("sub_app_builder/get_event_template/".$event['id']."/".$evname."/".base64_encode($event['event_desc'])."/edit", lang('event_edit'));?></td>
					</tr>
					<?php $u++;?>
					<?php endforeach?>
					<?php else: ?>
        			<p>
          				<?php echo lang('admin_no_events');?>
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
	<?php if($message) {?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message",
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