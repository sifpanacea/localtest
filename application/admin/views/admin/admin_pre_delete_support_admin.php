<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Delete Admin";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["support admin management"]["sub"]["deleteadmin"]["active"] = true;
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
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-0" data-widget-editbutton="false">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-user"></i> </span>
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
					<?php $u = 0;?>
					<?php if ($support_admin): ?>
					<tr>
						 <th><?php echo lang('support_admin_first_name');?></th>
						<th><?php echo lang('support_admin_last_name');?></th>
						<th><?php echo lang('support_admin_username');?></th>
						<th><?php echo lang('support_admin_email');?></th>
						<th><?php echo lang('support_admin_mobile');?></th>
						<th><?php echo lang('support_admin_level');?></th>
						<th><?php echo lang('support_admin_delete_index');?></th>
					</tr>
					<?php foreach ($support_admin as $user):?>
                    <tbody>
					<tr>
						<td><?php echo $user->first_name;?></td>
						<td><?php echo $user->last_name;?></td>
						<td><?php echo $user->username;?></td>
						<td><?php echo $user->email;?></td>
						<td><?php echo $user->phone;?></td>
						<td><?php echo $user->level;?></td>
						<td id="deleteadmin"><a  class='delete' href='<?php echo URL."admin_dash/delete_support_admin/".$user->id;?>'>
                			<?php echo lang('support_admin_delete')?>
                			</a>
                		</td>
					</tr>
					<?php $u++;?>
					<?php endforeach;?>
					<?php else: ?>
        			<p>
          				<?php echo lang('admin_no_users');?>
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

// Delete Support Admin 
	$('#deleteadmin a').click(function(e) {
		//get the link
		var $this = $(this);
		$.delURL = $this.attr('href');

		// ask verification
		$.SmartMessageBox({
			title : "<i class='fa fa-minus-square txt-color-orangeDark'></i> Do you want to delete this admin ?",
			buttons : '[No][Yes]'

		}, function(ButtonPressed) {
			if (ButtonPressed == "Yes") {
				setTimeout(deletemyapps, 1000)
			}

		});
		e.preventDefault();
	});

	/*
	 * Delete Support Admin ACTION
	 */

	function deletemyapps() {
		window.location = $.delURL;
	}
</script>

<?php 
	//include footer
	include("inc/footer.php"); 
?>